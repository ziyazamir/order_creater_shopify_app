<?php

namespace App\Http\Controllers;

use App\Models\Orders_Creates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class OrdersCreatesController extends Controller
{
    //
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
    public function readcsv(Request $request)
    {
        // echo "heelo";
        $shop = Auth::user();
        $pr_request = $shop->api()->rest('GET', '/admin/products.json');
        // $pr_request = $shop->api()->graph('{ shop { name } }');
        // echo json_encode($pr_request['body']);
        // json_decode($request['body']);
        // print_r($request['body']);
        // die();
        // print_r($request);
        // $shop = $request->store;
        if ($request->file("file")) {
            $file = $request->file('file');
            $orderArr = $this->csvToArray($file);
            foreach ($orderArr as $item) {
                $name = $item['Full Name'];
                $pincode = $item['Pincode/ZIP'];
                $phone = $item['Phone'];
                $phone = trim($phone, "*");
                $payment_method = $item['Payment Method'];
                $billing_adddres = $item['Town/City'];
                $state = $item['State'];
                $email = $item['Email'];
                $country = $item['Country'];
                $variant_id = $item['variant_id'];
                // echo $variant_id;
                $variant_id = trim($variant_id, "*");
                // echo $variant_id;
                // $variant_id = '42125563003087';
                $quantity = $item['Quantity'];
                $ip_address = $item['ip'];


                $order  = new stdClass();
                $order->processing_method = "manual";
                $order->gateway = $payment_method;
                $order->financial_status = "pending";
                $order->note_attributes = [];
                $note = new stdClass;
                $note->name = "IP Address";
                $note->value = $ip_address;
                array_push($order->note_attributes, $note);
                $order->line_items = array(
                    array(
                        "variant_id" => $variant_id,
                        "Quantity" => $quantity
                    )
                );
                @$order->customer->first_name = $name;
                @$order->customer->email = $email;
                // $order->shipping_address = new stdClass;
                @$order->shipping_address->first_name = $name;
                @$order->shipping_address->last_name = $name;
                @$order->shipping_address->address1 = $billing_adddres;
                @$order->shipping_address->phone = $phone;
                @$order->shipping_address->city = $state;
                @$order->shipping_address->province = $state;
                @$order->shipping_address->country = $country;
                @$order->shipping_address->zip = $pincode;
                // @$order->billing_address = new stdClass;

                @$order->billing_address->first_name = $name;
                @$order->billing_address->last_name = $name;
                @$order->billing_address->address1 = $billing_adddres;
                @$order->billing_address->phone = $phone;
                @$order->billing_address->city = $state;
                @$order->billing_address->province = $state;
                @$order->billing_address->country = $country;
                @$order->billing_address->zip = $pincode;
                // @$order->shipping_address = $adddres;
                // @$order->billing_address = $adddres;
                @$order->payment_gateway_names = [$payment_method];

                $data = array(
                    "order" => $order
                );
                echo json_encode($data);
                // die();
                // $data->order = $order;
                // $shop = Auth::user();
                // return $shop;
                // die();
                $order_request = $shop->api()->rest('POST', 'admin/orders.json', $data);
                $response = $order_request['body'];
                // $order_request = $shop->api()->rest('POST', '/admin/api/customers/customer.json', ['customer' => "phone:{$phone}"]);
                // echo json_encode($order_request['body']);
                // echo gettype($order_request['body']);
                $order_details = new Orders_Creates();
                $order_details->customer_name = $name;
                $order_details->email = $email;
                $order_details->phone = $phone;
                $order_details->ip = $ip_address;
                $order_details->created_at = date('d/m/Y h:i:sa');
                $order_details->ip = $ip_address;
                if (isset($response->order)) {
                    $succes_order = $response->order;
                    $order_details->order_id = $succes_order->id;
                    $order_details->status = 1;
                    $transaction = new stdClass;
                    $transaction->order_id = $succes_order->id;
                    $transaction->kind = "sale";
                    $transaction->source = "external";
                    $transaction->status = "pending";
                    $transaction->gateway = "Cash On Delivery COD";
                    $transaction_data = array(
                        "transaction" => $transaction
                    );
                    $transaction_request = $shop->api()->rest('POST', 'admin/orders/' . $succes_order->id . '/transactions.json', $transaction_data);
                    $transaction_resp = $transaction_request['body'];
                    // echo json_encode($transaction_resp);
                    echo "order created successfully" . "<br>";
                } else {
                    // $order_details->order_id = $succes_order->id;
                    $order_details->status = 0;
                    $order_details->error = json_encode($response);
                    echo "something went wrong" . "<br>";
                }
                $order_details->save();
                // echo json_encode($order_request['body']);
                sleep(2);
            };
        } else {
            echo "file not uploaded";
        }
        return back()->with("order", "success");
        // return redirect()->route("all_orders");
        die();
    }

    public function all_orders()
    {
        // $shop = $_GET['shop'];
        // echo $shop;
        $orders = Orders_Creates::all()->reverse();
        return view("all_orders", ["orders" => $orders,]);
    }
}
