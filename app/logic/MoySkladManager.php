<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * SupplierRequestManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';

class MoySkladManager extends BaseManager {

    public function createPurchaseOrder($data) {
        $login ='admin@ovpoda';
        $password = 'cf25d9749c';


        $sklad = MoySklad\MoySklad::getInstance($login, $password);

        $agent = MoySklad\Entities\Counterparty::query($sklad)->byId("ad420481-39cc-11e8-9ff4-34e800190914");
        $organization = MoySklad\Entities\Organization::query($sklad)->byId("ad40847a-39cc-11e8-9ff4-34e800190911");

        $meta = $agent->getMetaData($sklad);

        $agentMetadata = $agent->getMetaData($sklad);

        $newPurchaseOrder = array(
            'name' => $data['id'],
            'externalCode' => $data['id']

        );

        try {
            $purchaseOrder = (new MoySklad\Entities\Documents\Orders\PurchaseOrder($sklad, $newPurchaseOrder))->buildCreation()
                ->addCounterparty($agent)
                ->addOrganization($organization)
                ->execute();
        } catch (GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            var_dump($responseBodyAsString);
        }

        return $purchaseOrder;
    }

}