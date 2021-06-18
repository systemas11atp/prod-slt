<?php 
require_once '/var/www/vhosts/' . $_SERVER['HTTP_HOST'] . '/httpdocs/Automatizacion/database/dbSelectors.php';
include dirname(__FILE__) . '/../../../config/config.inc.php';
include dirname(__FILE__) . '/../../../classes/order/Order.php';
include dirname(__FILE__) . '/../../../classes/order/OrderHistory.php';
include_once dirname(__FILE__) . '/../../../config/settings.inc.php';

$sql =   "SELECT po.id_order, po.reference, po.total_paid, po.total_paid_tax_incl, po.total_paid_tax_excl, po.total_paid_real, po.total_products, po.total_products_wt, pop.amount ";
$sql .=  "FROM prstshp_orders po  ";
$sql .=  "INNER JOIN prstshp_order_payment pop on pop.order_reference = po.reference ";
$sql .=  "WHERE po.id_customer IN (15,16) AND po.valid = 0 ";
print_r("sql :: {$sql}<br>");
$resultados = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

foreach ($resultados as $resultado) {
    $id_order = $resultado[id_order];
    $reference = $resultado[reference];
    $total_paid = $resultado[total_paid];
    $total_paid_tax_incl = $resultado[total_paid_tax_incl];
    $total_paid_tax_excl = $resultado[total_paid_tax_excl];
    $total_paid_real = $resultado[total_paid_real];
    $total_products = $resultado[total_products];
    $total_products_wt = $resultado[total_products_wt];
    $amount = $resultado[amount];
    $maximo = $amount + 0.1;
    $minimo = $amount - 0.1;
    if($total_paid >=$minimo && $total_paid <= $maximo){
        $sql ="UPDATE prstshp_orders SET total_paid = {$amount}, total_paid_tax_incl = {$amount}, total_products_wt = {$amount} WHERE id_order = {$id_order}";
        if(Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)){
            print_r("1 :: sql :: {$sql}<br>");
            $objOrder = new Order($id_order); //order with id=1
            $history = new OrderHistory();
            $history->id_order = (int)$objOrder->id;
            $history->changeIdOrderState(2, (int)($objOrder->id));
        }else{
            print_r("2 :: sql :: {$sql}<br>");
        }

    }
}
?>