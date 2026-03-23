<?php

class AddressLookupService
{
    public static function getAddressDataByCep($cep)
    {
        $address = CEPService::get($cep);

        if (!$address)
        {
            return null;
        }

        $coordinates = self::locateCoordinates($address->rua ?? null, $address->bairro ?? null, $address->cidade_id ?? null);

        if ($coordinates)
        {
            $address->longitude = $coordinates->longitude;
            $address->latitude = $coordinates->latitude;
        }

        return $address;
    }

    public static function locateCoordinates($rua, $bairro, $cidade_id)
    {
        if (empty($rua) && empty($bairro) && empty($cidade_id))
        {
            return null;
        }

        $query = self::buildQuery($rua, $bairro, $cidade_id);

        if (empty($query))
        {
            return null;
        }

        $result = BuilderHttpClientService::get(
            'https://nominatim.openstreetmap.org/search',
            [
                'q' => $query,
                'format' => 'jsonv2',
                'limit' => 1,
                'countrycodes' => 'br'
            ],
            null,
            [
                'User-Agent: portaltransparencia/1.0',
                'Accept-Language: pt-BR'
            ]
        );

        if (empty($result) || empty($result[0]))
        {
            return null;
        }

        $coordinates = new stdClass;
        $coordinates->longitude = $result[0]->lon ?? null;
        $coordinates->latitude = $result[0]->lat ?? null;

        return $coordinates;
    }

    private static function buildQuery($rua, $bairro, $cidade_id)
    {
        $parts = [];

        if (!empty($rua))
        {
            $parts[] = trim($rua);
        }

        if (!empty($bairro))
        {
            $parts[] = trim($bairro);
        }

        if (!empty($cidade_id))
        {
            $cidade = new Cidade($cidade_id);

            if (!empty($cidade->nome))
            {
                $parts[] = $cidade->nome;
            }

            if (!empty($cidade->estado->sigla))
            {
                $parts[] = $cidade->estado->sigla;
            }
        }

        $parts[] = 'Brasil';

        return implode(', ', array_filter($parts));
    }
}
