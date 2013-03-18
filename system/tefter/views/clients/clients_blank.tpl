<h2>{if $user.level eq 99}{$lang.message_havent_created_clients_yet}{else}{$lang.message_no_clients_to_show}{/if}</h2>

{if $user.level eq 99}
<p class="instruction">{$lang.message_click_to_add_first_client}</p>
{/if}