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
                $phone = sprintf("%d", $item['Phone']);
                $payment_method = $item['Payment Method'];
                $billing_adddres = $item['Town/City'];
                $state = $item['State'];
                $email = $item['Email'];
                $country = $item['Country'];
                // $variant_id = sprintf("%d", $item['variant_id']);
                $variant_id = '42125563003087';
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
                $adddres = new stdClass;
                $adddres->first_name = $name;
                $adddres->last_name = "";
                $adddres->address1 = $billing_adddres;
                $adddres->phone = $phone;
                $adddres->city = $state;
                $adddres->province = $state;
                $adddres->country = $country;
                $adddres->zip = $pincode;
                $order->shipping_address = $adddres;
                $order->billing_address = $adddres;
                $order->payment_gateway_names = [$payment_method];

                $data = array(
                    "order" => $order
                );
                // $data->order = $order;
                // $shop = Auth::user();
                // return $shop;
                // die();
                $order_request = $shop->api()->rest('POST', 'admin/orders.json', $data);
                // $order_request = $shop->api()->rest('POST', '/admin/api/customers/customer.json', ['customer' => "phone:{$phone}"]);
                // echo $order_request['body']['customers'];

                $order_details = new Orders_Creates();
                $order_details->customer_name = $name;
                $order_details->email = $email;
                $order_details->phone = $phone;
                $order_details->ip = $ip_address;
                $order_details->save();
                echo "<pre>";
                print_r($data);
                return json_encode($order_request['body']);
                echo "</pre>";
                break;
            };
        } else {
            echo "file not uploaded";
        }
        die();
    }
}
