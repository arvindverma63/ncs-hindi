<!DOCTYPE html>
<html>

<head>
    <title>Seekers List</title>
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
    </style>
</head>

<body>
    <h2>Seekers Data Report</h2>
    <p>Generated on: {{ date('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>City</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($seekers as $index => $seeker)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $seeker->user->name ?? '-' }}</td>
                    <td>{{ $seeker->user->email ?? '-' }}</td>
                    <td>{{ $seeker->company_name }}</td>
                    <td>{{ $seeker->city }}</td>
                    <td>{{ $seeker->is_verified ? 'Verified' : 'Unverified' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
