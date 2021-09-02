<?php


namespace Wontonee\Payu\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\Config;

class PayuController extends Controller
{

	/**
	 * OrderRepository $orderRepository
	 *
	 * @var \Webkul\Sales\Repositories\OrderRepository
	 */
	protected $orderRepository;
	/**
	 * InvoiceRepository $invoiceRepository
	 *
	 * @var \Webkul\Sales\Repositories\InvoiceRepository
	 */
	protected $invoiceRepository;

	/**
	 * Create a new controller instance.
	 *
	 * 
	 * @return void
	 */
	public function __construct(OrderRepository $orderRepository,  InvoiceRepository $invoiceRepository)
	{
		$this->orderRepository = $orderRepository;
		$this->invoiceRepository = $invoiceRepository;
	}

	/**
	 * Redirects to the paytm server.
	 *
	 * @return \Illuminate\View\View
	 */

	public function redirect()
	{
		$cart = Cart::getCart();


		$billingAddress = $cart->billing_address;

		$MERCHANT_KEY = core()->getConfigData('sales.paymentmethods.payu.payu_merchant_key');
		$SALT = core()->getConfigData('sales.paymentmethods.payu.salt_key');


		if (core()->getConfigData('sales.paymentmethods.payu.payu-website') == "Sandbox") :
			$PAYU_BASE_URL = "https://sandboxsecure.payu.in";        // For Sandbox Mode
		else :
			$PAYU_BASE_URL = "https://secure.payu.in";        // For Sandbox Mode
		endif;

		$shipping_rate = $cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0; // shipping rate
		$discount_amount = $cart->discount_amount; // discount amount
		$total_amount =  ($cart->sub_total + $cart->tax_total + $shipping_rate) - $discount_amount; // total amount

		$posted  = array(
			"key" => $MERCHANT_KEY,
			"txnid" => $cart->id,
			"amount" => $total_amount,
			"firstname" => $billingAddress->name,
			"email" => $billingAddress->email,
			"phone" => $billingAddress->phone,
			"surl" => route('payu.success'),
			"furl" => route('payu.failure'),
			"curl" => "cancel",
			"hash" => '',
			"productinfo" => 'Bagisto Order no ' . $cart->id
		);
		/*** has  */
		// Hash Sequence
		$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

		$hashVarsSeq = explode('|', $hashSequence);
		$hash_string = '';
		foreach ($hashVarsSeq as $hash_var) {
			$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
			$hash_string .= '|';
		}

		$hash_string .= $SALT;
		$hash = strtolower(hash('sha512', $hash_string));
		$action = $PAYU_BASE_URL . '/_payment';
		return view('payu::payu-redirect')->with(compact('MERCHANT_KEY', 'SALT', 'action', 'posted', 'hash'));
	}

	/**
	 * success
	 */
	public function success()
	{

		$order = $this->orderRepository->create(Cart::prepareDataForOrder());
		$this->orderRepository->update(['status' => 'processing'], $order->id);
		if ($order->canInvoice()) {
			$this->invoiceRepository->create($this->prepareInvoiceData($order));
		}
		Cart::deActivateCart();
		session()->flash('order', $order);
		// Order and prepare invoice
		return redirect()->route('shop.checkout.success');
	}

	/**
	 * failure
	 */
	public function failure()
	{
		session()->flash('error', 'Payu payment either cancelled or transaction failure.');
		return redirect()->route('shop.checkout.cart.index');
	}

	/**
	 * Prepares order's invoice data for creation.
	 *
	 * @return array
	 */
	protected function prepareInvoiceData($order)
	{
		$invoiceData = ["order_id" => $order->id,];

		foreach ($order->items as $item) {
			$invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
		}

		return $invoiceData;
	}
}