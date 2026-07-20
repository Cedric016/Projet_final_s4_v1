<?php

if (!function_exists('client_connecte')) {
    function client_connecte(): ?array
    {
        $session = service('session');
        $telephone = $session->get('client_telephone');

        if (empty($telephone)) {
            return null;
        }

        $clientModel = new \App\Models\ClientModel();
        return $clientModel->findByTelephone($telephone);
    }
}

if (!function_exists('client_deconnecter')) {
    function client_deconnecter(): void
    {
        service('session')->remove('client_telephone');
    }
}
