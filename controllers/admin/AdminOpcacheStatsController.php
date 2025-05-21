<?php

class AdminOpcacheStatsController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;
        $this->meta_title = $this->l('Statut OpCache');
    }

    public function initContent()
    {
        parent::initContent();

        if (!function_exists('opcache_get_status') || !function_exists('opcache_get_configuration')) {
            $this->errors[] = $this->l('OpCache n\'est pas activé sur ce serveur.');
            return;
        }

        // Si on a cliqué sur "Vider le cache"
        if (Tools::isSubmit('reset_opcache') && function_exists('opcache_reset')) {
            if (opcache_reset()) {
                $this->confirmations[] = $this->l('Le cache OpCache a été vidé avec succès.');
            } else {
                $this->errors[] = $this->l('Impossible de vider le cache OpCache.');
            }
        }

        $status = opcache_get_status(true);
        $config = opcache_get_configuration();

        $this->context->smarty->assign([
            'opcache_config' => $config,
            'opcache_status' => $status,
            'reset_link' => $this->context->link->getAdminLink('AdminOpcacheStats', true) . '&reset_opcache=1',
        ]);

        $this->setTemplate('module:sj4webopcachemonitor/views/templates/admin/opcache_stats.tpl');
    }
}
