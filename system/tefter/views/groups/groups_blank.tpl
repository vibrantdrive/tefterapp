<h2>{if $user.level gt 20}{$lang.message_havent_created_groups_yet}{else}{$lang.message_no_groups_to_show}{/if}</h2>

{if $user.level gt 20}
<p class="instruction">{$lang.message_click_to_add_first_group}</p>
{/if}