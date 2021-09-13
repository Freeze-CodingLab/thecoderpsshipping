{*
* Project : everpsshippingperpostcode
* @author Team EVER
* @copyright Team EVER
* @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
* @link https://www.team-ever.com
*}
<div class="alert alert-info" id="depots-list">
    {* {var_dump($cities)} *}
    <table id="carrier_depot_list" class="table table-striped table-bordered table-labeled hidden-sm-down">
        <th>{l s="City Name" mod="thecoderpsshipping"}</th>
        <th>{l s="Delivery Time" mod="thecoderpsshipping"}</th>
        <th>{l s="Price" mod="thecoderpsshipping"}</th>
        <th>{l s="Select" mod="thecoderpsshipping"}</th>
        {foreach from=$cities item=city}
            <tr>
                <td>
                    {$city['cityName']|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$city[0]->getDeliveryTime()|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {$city[0]->getPrice()|escape:'htmlall':'UTF-8'}
                </td>
                <td>
                    {* {$city[0]->getPrice()|escape:'htmlall':'UTF-8'} *}
                    <input type="radio" name="thecoderpsshipping"
                        value="{$city[0]->getThecoderpsshipping()->getId()|escape:'htmlall':'UTF-8'}" {if condition}
                        {/if} />
                </td>
            </tr>
        {/foreach}
    </table>
</div>