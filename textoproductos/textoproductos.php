<?php

/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class Textoproductos extends Module {
  public function __construct(){
    $this->name = 'textoproductos';
    $this->tab = 'others';
    $this->author = 'Raul';
    $this->version = '1.0.0';
    $this->need_instance = 0;
    $this->bootstrap = true;
    parent::__construct();
    $this->displayName = $this->l('Texto para productos');
    $this->description = $this->l('Configurar un texto para mostrar en la ficha de producto. Cada producto va a tener su texto independiente');
    $this->confirmUninstall = $this->l('Seguro que desea desinstalar?');
    $this->ps_versions_compliancy = array('min' => '1.7.1', 'max' => _PS_VERSION_);
  }

  public function install(){
    return parent::install() &&
      $this->instalarSql() &&
      $this->registerHook('displayProductButtons') &&
      $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle');
  }

  public function uninstall(){
    return parent::uninstall() && $this->borrarSql();
  }

  protected function instalarSql(){
    $returnSql = Db::getInstance()->execute("ALTER TABLE " . _DB_PREFIX_ . "product " . "ADD mitexto VARCHAR(255) NULL");
    return $returnSql;
  }

  protected function borrarSql(){
    $returnSql = Db::getInstance()->execute("ALTER TABLE " . _DB_PREFIX_ . "product " . "DROP mitexto");
    return $returnSql;
  }

  public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params){
    $product = new Product($params['id_product']);
    $this->context->smarty->assign(array('mitexto' => $product->mitexto));
    return $this->display(__FILE__, 'views/templates/hook/textoBackoffice.tpl');
  }

  public function hookDisplayProductButtons(){
    return $this->display(__FILE__, 'views/templates/hook/textoProducto.tpl');
  }
}
