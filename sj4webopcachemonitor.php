<?php
/**
 * Module : sj4webopcachemonitor
 * Description : Affiche les stats OpCache dans le BO PrestaShop
 * Auteur : SJ4WEB.FR
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Sj4webOpcacheMonitor extends Module
{
    public function __construct()
    {
        $this->name = 'sj4webopcachemonitor';
        $this->version = '1.0.0';
        $this->author = 'SJ4WEB.FR';
        $this->need_instance = 0;
        $this->tab = 'administration';
        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = 'SJ4WEB - OpCache Monitor';
        $this->description = 'Affiche l\'état de l\'OpCache PHP dans le BO.';
        $this->bootstrap = true;
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayBackOfficeHeader');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminOpcacheStats'));
    }
}