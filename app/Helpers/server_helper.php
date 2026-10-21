<?php

function getServers($server)
{
    $tk_servers = [
        'server_colonnade'  => 'tk_cebo',
        'cebu'              => 'tk_cebo',
        'server_tag'        => 'tk_corp',
        'tagbilaran'        => 'tk_corp',
        'server_tal'        => 'tk_talibon',
        'talibon'           => 'tk_talibon',
        'server_tub'        => 'tk_tubigon',
        'tubigon'           => 'tk_tubigon'
    ];
    $server1 = in_array($server, ['cebu', 'server_colonnade']) ? 'pis_cebo' : 'pis_corp';
    $server2 = in_array($server, ['cebu', 'server_colonnade']) ? 'tk_cebo' : 'tk_corp';
    $server3 = $tk_servers[$server] ?? null;
    $hrCode = in_array($server, ['cebu', 'server_colonnade']) ? 'cebo' : 'asc';

    return ['pis' => $server1, 'tk' => $server3, 'tk_loc' => $server2, 'hrCode' => $hrCode];
}
