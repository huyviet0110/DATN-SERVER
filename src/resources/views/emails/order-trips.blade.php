<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn đặt vé xe khách</title>

    <style>
        .tbl-receiver-info, .tbl-order-trips {
            text-align: center;
            border: 1px solid #000000;
            margin-bottom: 20px;
            width: 80%;
        }

        .tbl-receiver-info {
            width: 40%;
        }

        .total-payment {
            color: blue;
        }

        .order-detail {
            margin-top: 20px;
            color: blue;
        }

        table {
            border-spacing: 0 !important;
        }

        td {
            padding: 0;
        }

        img {
            border-radius: 0;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #eee;
        }

        .p-2 {
            padding: 0.5rem !important;
        }

        * {
            font-size: 14px;
            font-family: 'M PLUS 1p', sans-serif;
        }

        @media (min-width:415.98px) {
            table.table-summary {
                width: auto !important;
            }

            table.table-summary tbody th {
                width: auto !important;
            }
        }

    </style>
</head>
<body>
<h1>
    Chi tiết đơn đặt vé
</h1>

<div class="wrapper">
    <table width="100%" style="max-width:600px;background-color: #fff;">
        <tbody>
        <!-- HEADER -->
        <tr>
            <td height="8" style="background-color: #156AC7;"></td>
        </tr>
        <!-- TITLE -->
        <tr>
            <td>
                <table width="100%" style="padding: 12px;">
                    <tbody>
                    <tr>
                        <td style="padding-bottom: 4px;line-height:16px;"><b
                                style="font-size: 18px;">Thông tin người nhận vé</b></td>
                    </tr>
                    <tr>
                        <td style="padding:0 12px">Tên: {{ $order->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0 12px">Số điện thoại: {{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0 12px">Email: {{ $order->email }}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td height="0.1" style="background-color: #156AC7;"></td>
        </tr>
        <tr>
            <td height="0.1" style="background-color: #156AC7;"></td>
        </tr>
        <tr>
            <td>
                <table style="padding: 12px 12px 4px;width:100%;">
                    <tbody>
                    <tr>
                        <td><b style="font-size: 18px;">Thông tin chuyến đi</b></td>
                    </tr>
                    </tbody>
                </table>
                <div style="padding: 0 24px 12px;">
                    <table border="1" style="border-color:#6eb4ff;border-collapse: collapse;">
                        <thead>
                        <th style="background-color: #d0e6f5;padding:0.5rem 1rem;white-space:nowrap;">STT</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;width: 100%;">Điểm khởi hành</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Điểm kết thúc</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Nhà xe</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Biển số xe</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Số chỗ ngồi</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Loại ghế</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Thời gian khởi hành</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Tổng thời gian di chuyển</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Điểm đón</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Thời gian đón</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Điểm trả</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Thời gian trả</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Giá</th>
                        <th style="background-color: #d0e6f5;padding:0.5rem;min-width: 110px;">Số chỗ đã đặt</th>
                        </thead>
                        <tbody>
                        @foreach ($order_details as $i => $order_detail)
                            <tr>
                                <td style="text-align: center">{{ $i + 1 }}</td>
                                <td style="padding:0.5rem;min-width: 110px;">{{ $order_detail->trip->journey->departureLocation->name }}</td>
                                <td style="padding:0.5rem;min-width: 110px;">{{ $order_detail->trip->journey->destinationLocation->name }}</td>
                                <td style="padding:0.5rem;min-width: 110px;">{{ $order_detail->trip->bus->admin->name }}</td>
                                <td style="padding:0.5rem;min-width: 110px;">{{ $order_detail->trip->bus->license_plate }}</td>
                                <td style="padding:0.5rem;min-width: 110px;text-align:right;">{{ $order_detail->trip->bus->seat_number }}</td>
                                <td style="padding:0.5rem;min-width: 110px;">{{ $order_detail->trip->bus->seat_type_name }}</td>
                                <td style="padding:0.5rem;min-width: 110px;text-align:right;">{{ $order_detail->trip->departure_time_formated }}</td>
                                <td style="padding:0.5rem;min-width: 110px;text-align:right;">{{ $order_detail->trip->total_time_formated }}</td>
                                <td style="padding:0.5rem;min-width: 110px;">{{ $order_detail->pick_up_place }}</td>
                                <td style="padding:0.5rem;min-width: 110px;text-align:right;">{{ $order_detail->pick_up_time_formated }}</td>
                                <td style="padding:0.5rem;min-width: 110px;">{{ $order_detail->drop_off_place }}</td>
                                <td style="padding:0.5rem;min-width: 110px;text-align:right;">{{ $order_detail->drop_off_time_formated }}</td>
                                <td style="padding:0.5rem;min-width: 110px;text-align:right;">{{ $order_detail->trip->price_formated }}</td>
                                <td style="padding:0.5rem;min-width: 110px;text-align:right;">{{ $order_detail->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <!-- FOOTER -->
        <tr>
            <td height="8" style="background-color: #156AC7;"></td>
        </tr>
        </tbody>
    </table>

    <h3>
        Tổng tiền phải thanh toán:
        <span class="total-payment">{{ $order->total_payment_formated }} VND</span>
    </h3>
</div>

</body>
</html>
