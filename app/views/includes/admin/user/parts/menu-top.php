<style>
 .menu-top ul {float: left; padding: 0;}
 .menu-top li {float: left; list-style: none; margin: 0 10px 0 0; font-size: 14px;}
 .menu-top li span {font-weight: bold;}

</style>
<div class="menu-top">
  <ul>
    <li><?=!empty($_GET['stats_type'])?'<a href="' . current_url() . '">Заказы</a>':'<span>Заказы</span>';?></li>
    <li><?=!empty($_GET['stats_type'])&&$_GET['stats_type']=='broadcast'?'<span>Рассылки</span>':'<a href="' . current_url() . '?stats_type=broadcast' . '">Рассылки</a>';?></li>
        <li><?=!empty($_GET['stats_type'])&&$_GET['stats_type']=='shop_pages'?'<span>Страницы в магазе</span>':'<a href="' . current_url() . '?stats_type=shop_pages' . '">Страницы в магазе</a>';?></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="clear"></div>