<!DOCTYPE html>
<html>

<head>
    <title>Coaches List</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .badge {
            padding: 2px 5px;
            font-size: 10px;
            border-radius: 3px;
        }

        .approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .pending {
            background-color: #fff3cd;
            color: #664d03;
        }

        .rejected {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>
</head>

<body>
    <h2>Registered Coaches Report</h2>
    <p>Generated on: {{ date('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Designation</th>
                <th>Company</th>
                <th>City</th>
                <th>Exp (Yrs)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coaches as $index => $coach)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $coach->user->name ?? '-' }}</td>
                    <td>{{ $coach->user->email ?? '-' }}</td>
                    <td>{{ $coach->designation }}</td>
                    <td>{{ $coach->company_name }}</td>
                    <td>{{ $coach->city }}</td>
                    <td>{{ $coach->experience_years }}</td>
                    <td>
                        <span class="badge {{ $coach->approval_status }}">
                            {{ ucfirst($coach->approval_status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
