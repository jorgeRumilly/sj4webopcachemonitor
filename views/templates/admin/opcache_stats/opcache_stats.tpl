{block name="content"}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{l s='Configuration OpCache' d='Modules.Sj4webopcachemonitor.Admin'}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                <tr><th>{l s='Clé' d='Modules.Sj4webopcachemonitor.Admin'}</th><th>{l s='Valeur' d='Modules.Sj4webopcachemonitor.Admin'}</th></tr>
                </thead>
                <tbody>
                {foreach from=$opcache_config.directives key=key item=value}
                    <tr>
                        <td>{$key}</td>
                        <td>{if is_array($value)}[Array]{else}{$value|escape:'html'}{/if}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">{l s='Statut OpCache' d='Modules.Sj4webopcachemonitor.Admin'}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                <tr><th>{l s='Clé' d='Modules.Sj4webopcachemonitor.Admin'}</th><th>{l s='Valeur' d='Modules.Sj4webopcachemonitor.Admin'}</th></tr>
                </thead>
                <tbody>
                {foreach from=$opcache_status key=key item=value}
                    <tr>
                        <td>{$key}</td>
                        <td>
                            {if is_array($value)}
                                [Array]
                            {else}
                                {$value|escape:'html'}
                            {/if}
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
            <a href="{$reset_link}" class="btn btn-warning mb-3">
                🔁 {l s='Vider le cache OpCache' d='Modules.Sj4webopcachemonitor.Admin'}
            </a>

        </div>
    </div>
{/block}
