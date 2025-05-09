<?php


namespace Wontonee\Payu\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Sales\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

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

		$MERCHANT_KEY = core()->getConfigData('sales.payment_methods.payu.payu_merchant_key');
		$SALT = core()->getConfigData('sales.payment_methods.payu.salt_key');
		$licenseKey = core()->getConfigData('sales.payment_methods.payu.license_keyid');

		if (core()->getConfigData('sales.payment_methods.payu.payu-website') == "Sandbox") :
			$PAYU_BASE_URL = "https://test.payu.in";        // For Sandbox Mode
		else :
			$PAYU_BASE_URL = "https://secure.payu.in";        // For Live Mode
		endif;

		$shipping_rate = $cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0; // shipping rate
		$discount_amount = $cart->discount_amount; // discount amount
		$total_amount =  ($cart->sub_total + $cart->tax_total + $shipping_rate) - $discount_amount; // total amount

		// Send the data to license server for checking license
		$posted = array(
			"license" => $licenseKey,
			'product_id' => 'PayuBagisto',
			"key" => $MERCHANT_KEY,
			'salt' => $SALT,
			"txnid" => $cart->id . '_' . now()->format('YmdHis'),
			"amount" => $total_amount,
			"firstname" => $billingAddress->name,
			"email" => $billingAddress->email,
			"phone" => $billingAddress->phone,
			"surl" => route('payu.success'),
			"furl" => route('payu.failure'),
			"curl" => route('payu.failure'),
			"hash" => '',
			"productinfo" => 'Bagisto Order no ' . $cart->id,
			'udf1' => '',
			'udf2' => '',
			'udf3' => '',
			'udf4' => '',
			'udf5' => '',
			'salt' => $SALT

		);

		// Validate license
		$response = Http::post('https://myapps.wontonee.com/api/process-payu-data', $posted);
		if ($response->successful()) {
			$responseData = $response->json();
			$hash = $responseData['data']['hash'];
			//dd($responseData['data']['hash']);
			/*** hash key generate  */
			//$hash = $this->getHashKey($posted);
			$action = $PAYU_BASE_URL . '/_payment';
			return view('payu::payu-redirect')->with(compact('MERCHANT_KEY', 'SALT', 'action', 'posted', 'hash'));
		} else {
			$responseData = $response->json();
			session()->flash('error', $responseData['error']);
			return redirect()->route('shop.checkout.cart.index');
		}
	}

	/**
	 * success
	 */
	public function success()
	{

		$cart = Cart::getCart();
		$data = (new OrderResource($cart))->jsonSerialize(); // new class v2.2
		$order = $this->orderRepository->create($data);
		$this->orderRepository->update(['status' => 'processing'], $order->id);
		if ($order->canInvoice()) {
			$this->invoiceRepository->create($this->prepareInvoiceData($order));
		}
		Cart::deActivateCart();
		session()->flash('order_id', $order->id);
		// Order and prepare invoice
		return redirect()->route('shop.checkout.onepage.success');
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
