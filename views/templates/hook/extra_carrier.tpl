{*
* Project : everpsshippingperpostcode
* @author Team EVER
* @copyright Team EVER
* @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
* @link https://www.team-ever.com
*}
<div class="alert alert-info" id="depots-list">
    {* {var_dump($cities)} *}
    {foreach from=$cities item=city}
        <li>{$city->getCityName()|escape:'htmlall':'UTF-8'}</li>
    {/foreach}
</div>