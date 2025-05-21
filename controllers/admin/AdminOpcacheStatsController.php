<?php

class AdminOpcacheStatsController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;
        $this->meta_title = $this->trans('Statut OpCache', [], 'Modules.Sj4webopcachemonitor.Admin');
    }

    public function initContent()
    {
        parent::initContent();

        if (!function_exists('opcache_get_status') || !function_exists('opcache_get_configuration')) {
            $this->errors[] = $this->trans('OpCache n\'est pas activé sur ce serveur.', [], 'Modules.Sj4webopcachemonitor.Admin');
            return;
        }

        // Si on a cliqué sur "Vider le cache"
        if (Tools::isSubmit('reset_opcache') && function_exists('opcache_reset')) {
            if (opcache_reset()) {
                $this->confirmations[] = $this->trans('Le cache OpCache a été vidé avec succès.', [], 'Modules.Sj4webopcachemonitor.Admin');
            } else {
                $this->errors[] = $this->trans('Impossible de vider le cache OpCache.', [], 'Modules.Sj4webopcachemonitor.Admin');
            }
        }

        $status = opcache_get_status(true);
        $config = opcache_get_configuration();

        $this->context->smarty->assign([
            'opcache_config' => $config,
            'opcache_status' => $status,
            'reset_link' => $this->context->link->getAdminLink('AdminOpcacheStats', true) . '&reset_opcache=1',
        ]);

        $this->setTemplate('opcache_stats.tpl');
    }
}
