<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title>{{ $title ?? 'NCS Hindi | Discovery Vault' }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;900&display=swap');

    :root {
        --bg: #050505;
        --card: #0d0d0f;
        --border: #1a1a1e;
        --grad-primary: linear-gradient(135deg, #b45309 0%, #991b1b 100%);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg);
        color: #efefef;
    }

    .font-brand {
        font-family: 'Outfit', sans-serif;
    }

    .btn-vault {
        background: var(--grad-primary);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(153, 27, 27, 0.4);
        border-radius: 12px;
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .forum-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 24px;
        transition: 0.3s;
    }

    .forum-card:hover {
        border-color: #b45309;
        background: #111114;
    }

    .stat-badge {
        background: rgba(180, 83, 9, 0.1);
        border: 1px solid rgba(180, 83, 9, 0.2);
        color: #f59e0b;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>
