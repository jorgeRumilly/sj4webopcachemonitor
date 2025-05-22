<?php

class AdminOpcacheStatsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->module = Module::getInstanceByName('sj4webopcachemonitor');
        $this->table = 'sj4webopcachemonitor';
        $this->bootstrap = true;
        $this->lang = false;
        parent::__construct();
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

        $status = $this->formatOpcacheStatus(opcache_get_status(true));

        $config = opcache_get_configuration();

        $this->context->smarty->assign([
            'opcache_config' => $this->buildOpcacheDirectiveTable($config['directives']),
            'opcache_status' => $this->buildOpcacheDirectiveTable($status),
            'opcache_status_raw' => $status, // on passe le brut aussi pour lire _alerts
            'reset_link' => $this->context->link->getAdminLink('AdminOpcacheStats', true) . '&reset_opcache=1',
        ]);
        $this->setTemplate('opcache_stats.tpl');

    }

    private function getOpcacheDirectiveMeta(): array
    {
        return [
            'opcache.enable' => [
                'comment' => 'Active le cache OpCode',
                'expected' => 1,
                'compare' => '==',
            ],
            'opcache.enable_cli' => [
                'comment' => 'Active OpCache pour le CLI (souvent désactivé)',
                'expected' => 0,
                'compare' => '==',
            ],
            'opcache.use_cwd' => ['comment' => 'Ajoute le répertoire courant à la clé du cache'],
            'opcache.validate_timestamps' => ['comment' => 'Vérifie les fichiers à chaque revalidation'],
            'opcache.validate_permission' => ['comment' => 'Valide les permissions fichiers avant cache'],
            'opcache.validate_root' => ['comment' => 'Vérifie que le même utilisateur utilise le cache'],
            'opcache.dups_fix' => ['comment' => 'Corrige les déclarations de classes en doublon'],
            'opcache.revalidate_path' => ['comment' => 'Vérifie le chemin complet des fichiers à chaque fois'],
            'opcache.log_verbosity_level' => ['comment' => 'Niveau de log OpCache (0 = off, 4 = max)'],
            'opcache.memory_consumption' => [
                'comment' => 'Taille mémoire du cache OpCode (en Mo)',
                'expected' => 256,
                'compare' => '>='
            ],
            'opcache.interned_strings_buffer' => [
                'comment' => 'Taille du buffer pour les chaînes internes (en Mo)',
                'expected' => 32,
                'compare' => '>='
            ],
            'opcache.max_accelerated_files' => [
                'comment' => 'Nombre max de fichiers PHP à mettre en cache',
                'expected' => 16229,
                'compare' => '>='
            ],
            'opcache.max_wasted_percentage' => [
                'comment' => 'Fragmentation mémoire tolérée (%)',
                'expected' => 10,
                'compare' => '<='
            ],
            'opcache.consistency_checks' => ['comment' => 'Fréquence des checks de cohérence du cache'],
            'opcache.force_restart_timeout' => ['comment' => 'Délai max avant redémarrage forcé'],
            'opcache.revalidate_freq' => [
                'comment' => 'Fréquence de revalidation (en secondes)',
                'expected' => 10,
                'compare' => '>='
            ],
            'opcache.preferred_memory_model' => ['comment' => 'Modèle mémoire préféré (non utilisé)'],
            'opcache.blacklist_filename' => ['comment' => 'Fichier de blacklist des fichiers à exclure'],
            'opcache.max_file_size' => [
                'comment' => 'Taille max des fichiers à cacher (0 = illimité)',
                'expected' => 0,
                'compare' => '=='
            ],
            'opcache.error_log' => ['comment' => 'Fichier de log des erreurs OpCache'],
            'opcache.protect_memory' => ['comment' => 'Empêche les écrasements mémoire du cache'],
            'opcache.save_comments' => ['comment' => 'Conserve les commentaires PHP (annotations)'],
            'opcache.record_warnings' => ['comment' => 'Log les avertissements à l’exécution'],
            'opcache.enable_file_override' => [
                'comment' => 'Override de include/require (souvent à désactiver)',
                'expected' => 0,
                'compare' => '=='
            ],
            'opcache.optimization_level' => ['comment' => 'Niveau d’optimisation des scripts PHP'],
            'opcache.lockfile_path' => ['comment' => 'Chemin vers le fichier de verrou'],
            'opcache.file_cache' => ['comment' => 'Chemin du cache de fichiers sur disque'],
            'opcache.file_cache_only' => ['comment' => 'Utilise uniquement le cache sur disque'],
            'opcache.file_cache_consistency_checks' => ['comment' => 'Vérifie la cohérence du cache disque'],
            'opcache.file_update_protection' => ['comment' => 'Délai avant qu’un fichier modifié soit pris en compte'],
            'opcache.opt_debug_level' => ['comment' => 'Niveau de debug interne'],
            'opcache.restrict_api' => ['comment' => 'Restreint les appels API à OpCache'],
            'opcache.huge_code_pages' => ['comment' => 'Active les pages mémoire énormes'],
            'opcache.preload' => ['comment' => 'Script à précharger au démarrage'],
            'opcache.preload_user' => ['comment' => 'Utilisateur Unix pour le preload'],
            'opcache.jit' => ['comment' => 'Active ou configure le moteur JIT'],
            'opcache.jit_buffer_size' => ['comment' => 'Taille du buffer mémoire pour le JIT'],
            'opcache.jit_debug' => ['comment' => 'Niveau de log debug JIT'],
            'opcache.jit_bisect_limit' => ['comment' => 'Limite de bisect pour tests'],
            'opcache.jit_blacklist_root_trace' => ['comment' => 'Blacklist des traces JIT principales'],
            'opcache.jit_blacklist_side_trace' => ['comment' => 'Blacklist des traces secondaires'],
            'opcache.jit_hot_func' => ['comment' => 'Seuil pour considérer une fonction comme chaude'],
            'opcache.jit_hot_loop' => ['comment' => 'Seuil de boucle chaude'],
            'opcache.jit_hot_return' => ['comment' => 'Seuil pour retour chaud'],
            'opcache.jit_hot_side_exit' => ['comment' => 'Seuil pour exit chaud'],
            'opcache.jit_max_exit_counters' => ['comment' => 'Nombre max d’exits JIT'],
            'opcache.jit_max_loop_unrolls' => ['comment' => 'Nombre max de dépliements de boucle'],
            'opcache.jit_max_polymorphic_calls' => ['comment' => 'Appels polymorphes autorisés max'],
            'opcache.jit_max_recursive_calls' => ['comment' => 'Appels récursifs max'],
            'opcache.jit_max_recursive_returns' => ['comment' => 'Retours récursifs max'],
            'opcache.jit_max_root_traces' => ['comment' => 'Traces principales max'],
            'opcache.jit_max_side_traces' => ['comment' => 'Traces secondaires max'],
            'opcache.jit_prof_threshold' => ['comment' => 'Seuil de déclenchement JIT'],

            // Clés du statut
            'opcache_enabled' => ['comment' => 'OpCache activé sur ce serveur','expected' => 1,'compare' => '=='],
            'cache_full' => ['comment' => 'La mémoire du cache est pleine'],
            'restart_pending' => ['comment' => 'Un redémarrage OpCache est requis'],
            'restart_in_progress' => ['comment' => 'Redémarrage OpCache en cours'],
            'memory_usage' => ['comment' => 'Utilisation de la mémoire OpCache'],
            'interned_strings_usage' => ['comment' => 'Utilisation mémoire des chaînes internes'],
            'opcache_statistics' => ['comment' => 'Statistiques globales du cache OpCache'],
            'opcache_statistics_start_time' => ['comment' => 'Date d’activation du cache'],
            'opcache_statistics_last_restart' => ['comment' => 'Dernier redémarrage du cache'],
            'scripts' => ['comment' => 'Nombre de scripts actuellement en cache'],
            'jit' => ['comment' => 'Statut et configuration du moteur JIT'],
        ];
    }

    private function buildOpcacheDirectiveTable(array $directives): array
    {
        $meta = $this->getOpcacheDirectiveMeta();
        $result = [];

        foreach ($directives as $key => $value) {
            $normValue = ($key === 'opcache.memory_consumption' && is_numeric($value))
                ? round($value / 1024 / 1024)
                : $value;

            $entry = [
                'key' => $key,
                'comment' => $meta[$key]['comment'] ?? '-',
                'value' => $normValue,
                'expected' => $meta[$key]['expected'] ?? '-',
                'state' => '-',
            ];

            if (isset($meta[$key]['expected'], $meta[$key]['compare'])) {
                $expected = $meta[$key]['expected'];
                $compare = $meta[$key]['compare'];
                switch ($compare) {
                    case '==':  $ok = $normValue == $expected; break;
                    case '>=':  $ok = $normValue >= $expected; break;
                    case '<=':  $ok = $normValue <= $expected; break;
                    default:    $ok = null;
                }
                $entry['state'] = ($ok === true) ? 'ok' : 'ko';
            }
            $result[] = $entry;
        }
        return $result;
    }

    private function formatOpcacheStatus(array $status): array
    {
        // 🔹 memory_usage lisible
        if (isset($status['memory_usage'])) {
            $mu = $status['memory_usage'];
            $status['memory_usage'] =
                'used: ' . round($mu['used_memory'] / 1024 / 1024) . ' Mo, ' .
                'free: ' . round($mu['free_memory'] / 1024 / 1024) . ' Mo, ' .
                'wasted: ' . round($mu['wasted_memory'] / 1024 / 1024) . ' Mo ' .
                '(' . $mu['current_wasted_percentage'] . ' %)';
        }

        // 🔹 interned_strings_usage lisible
        if (isset($status['interned_strings_usage'])) {
            $is = $status['interned_strings_usage'];
            $status['interned_strings_usage'] =
                'used: ' . round($is['used_memory'] / 1024 / 1024) . ' Mo, ' .
                'free: ' . round($is['free_memory'] / 1024 / 1024) . ' Mo, ' .
                'buffer size: ' . round($is['buffer_size'] / 1024 / 1024) . ' Mo';
        }

        /* On affiche juste le nombre de scripts */
        if (isset($status['opcache_statistics']['num_cached_scripts'])) {
            $nb = $status['opcache_statistics']['num_cached_scripts'];
            $status['scripts'] = $nb . ' scripts en cache';
        }

        // 🔹 opcache_statistics résumé + dates
        if (isset($status['opcache_statistics']) && is_array($status['opcache_statistics'])) {
            $s = $status['opcache_statistics'];

            $cached = $s['num_cached_scripts'] ?? '-';
            $hits_percent = isset($s['hits_percent']) ? round($s['hits_percent'], 2) . '%' : '-';
            $misses = $s['misses'] ?? '-';
            $oom = $s['oom_restarts'] ?? 0;
            $manual = $s['manual_restarts'] ?? 0;

            $status['opcache_statistics'] =
                "scripts: $cached, hits: $hits_percent, misses: $misses, restarts: $oom OOM / $manual manuel";

            if (isset($s['start_time'])) {
                $status['opcache_statistics_start_time'] = date('Y-m-d H:i:s', (int)$s['start_time']);
            }

            if (!empty($s['last_restart_time'])) {
                $status['opcache_statistics_last_restart'] = date('Y-m-d H:i:s', (int)$s['last_restart_time']);
            }
        }

        // 🔹 JIT résumé
        if (isset($status['jit']) && is_array($status['jit'])) {
            $jit = $status['jit'];

            $enabled = !empty($jit['enabled']) ? 'yes' : 'no';
            $buffer_total = isset($jit['buffer_size']) ? round($jit['buffer_size'] / 1024 / 1024) . ' Mo' : '-';
            $buffer_free = isset($jit['buffer_free']) ? round($jit['buffer_free'] / 1024 / 1024) . ' Mo' : '-';
            $level = $jit['opt_level'] ?? '-';

            $status['jit'] = "enabled: $enabled, buffer: $buffer_total (free: $buffer_free), opt_level: $level";
        }

        /* Calcul des alertes propres à l'opcache */
        $alerts = [];
        foreach (['opcache_enabled', 'cache_full', 'restart_pending', 'restart_in_progress'] as $key) {
            if (isset($status[$key]) && is_array($status[$key]) && in_array($status[$key]['state'], ['ko', 'warn'])) {
                $alerts[] = [
                    'key' => $status[$key]['key'],
                    'state' => $status[$key]['state'],
                    'value' => $status[$key]['value'],
                    'expected' => $status[$key]['expected'],
                ];
            }
        }

        // On ajoute dans $status un champ réservé pour le template
        $status['_alerts'] = $alerts;


        return $status;
    }

}
