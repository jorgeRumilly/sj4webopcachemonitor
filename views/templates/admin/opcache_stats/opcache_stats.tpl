{block name="content"}
    <div class="panel" id="opcache_stats_config">
        <div class="panel-heading">
            <i class="icon-plug"></i> {l s='Configuration OpCache' d='Modules.Sj4webopcachemonitor.Admin'}
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th>{l s='Clé' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='Explication' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='Valeur' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='Préconisation' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='État' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$opcache_config item=row}
                    <tr>
                        <td>{$row.key}</td>
                        <td>{$row.comment}</td>
                        <td>{$row.value}</td>
                        <td>{$row.expected}</td>
                        <td>
                            {if $row.state == 'ok'}
                                ✅
                            {elseif $row.state == 'ko'}
                                ❌
                            {else}
                                &mdash;
                            {/if}
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel mt-4" id="opcache_stats_status">
        <div class="panel-heading">
            <i class="icon-list-alt"></i> {l s='Statut OpCache' d='Modules.Sj4webopcachemonitor.Admin'}
        </div>
        <div class="panel-body">

            {if $opcache_status_raw._alerts|@count > 0}
                <div class="alert alert-danger" role="alert">
                    <strong>{l s='Anomalies détectées dans OpCache :' d='Modules.Sj4webopcachemonitor.Admin'}</strong><br>
                    <ul class="mb-0">
                        {foreach from=$opcache_status_raw._alerts item=alert}
                            <li>
                                <strong>{$alert.key}</strong> = {$alert.value}
                                ({l s='attendu :' d='Modules.Sj4webopcachemonitor.Admin'} {$alert.expected})
                                {if $alert.state == 'ko'} ❌{elseif $alert.state == 'warn'} ⚠️{/if}
                            </li>
                        {/foreach}
                    </ul>
                </div>
            {else}
                <div class="alert alert-success" role="alert">
                    <strong>{l s='Aucune anomalie détectée dans OpCache.' d='Modules.Sj4webopcachemonitor.Admin'}</strong>
                </div>
            {/if}


            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th>{l s='Clé' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='Explication' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='Valeur' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='Préconisation' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                    <th>{l s='État' d='Modules.Sj4webopcachemonitor.Admin'}</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$opcache_status item=row}
                    <tr>
                        <td>{$row.key}</td>
                        <td>{$row.comment}</td>
                        <td>{if is_array($row.value)}
                                <a href="#" data-toggle="modal" data-target="#modal_{$row.key|escape:'htmlall'}">
                                    [Voir détail]
                                </a>
                                <div class="modal fade" id="modal_{$row.key|escape:'htmlall'}" tabindex="-1" role="dialog"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content" style="max-height: 70vh; overflow: hidden;">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{$row.key}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="overflow-y: auto; max-height: 60vh;">
                                                <pre>{$row.value|@print_r|escape:'htmlall'}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {else}
                                {$row.value|escape:'html'}
                            {/if}
                        </td>
                        <td>{$row.expected}</td>
                        <td>
                            {if $row.state == 'ok'}
                                ✅
                            {elseif $row.state == 'ko'}
                                ❌
                            {else}
                                &mdash;
                            {/if}
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <a href="{$reset_link}" class="btn btn-warning bg-warning mb-3 pull-right">
                🔁 {l s='Vider le cache OpCache' d='Modules.Sj4webopcachemonitor.Admin'}
            </a>
        </div>
    </div>
{/block}
