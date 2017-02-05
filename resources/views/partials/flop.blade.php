<div class="t0" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop1') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop1')/100))}}%">
</div>

<div class="t1" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop2') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop2')/100))}}%">
</div>

<div class="t2" style="display: block; background-position: {{(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop3') % 100 - 2) * 100/12}}% {{25 * (round(\App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('flop3')/100))}}%">
</div>