<?php
// --- DATABASE CONFIGURATION ---
$host     = '127.0.0.1:3307';
$dbname   = 'emergency_db';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// --- HANDLE QUERY PARAMETERS ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'active'; // active, favorites, all

// --- BUILD QUERY ---
$query = "SELECT * FROM contacts WHERE 1=1";
$params = [];

// Filter setups
if ($filter === 'active') {
    $query .= " AND name NOT LIKE 'Blank Slot%'";
} elseif ($filter === 'favorites') {
    $query .= " AND is_favorite = 1";
}

// Search setups
if (!empty($search)) {
    $query .= " AND (name LIKE :search OR number LIKE :search OR email LIKE :search OR description LIKE :search)";
    $params['search'] = "%$search%";
}

$query .= " ORDER BY is_favorite DESC, name ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$contacts = $stmt->fetchAll();

// Get active contact for detail viewer pane (Defaults to first entry in current view)
$selected_id = isset($_GET['id']) ? intval($_GET['id']) : (count($contacts) > 0 ? $contacts[0]['id'] : null);
$selected_contact = null;

if ($selected_id) {
    foreach ($contacts as $c) {
        if ($c['id'] == $selected_id) {
            $selected_contact = $c;
            break;
        }
    }
    // Fallback fetch if chosen ID isn't matching the filtered view list
    if (!$selected_contact) {
        $detail_stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
        $detail_stmt->execute([$selected_id]);
        $selected_contact = $detail_stmt->fetch();
    }
}
?>


<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Directory Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="h-full flex text-gray-800">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0 border-r border-slate-800">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-slate-800 gap-3">
                <div class="bg-red-500/20 p-2 rounded-lg text-red-400">
                    <i class="ph-bold ph-first-aid text-xl"></i>
                </div>
                <h1 class="font-bold text-white tracking-wide">Contacts Hub  </h1>
            </div>
            
            <nav class="mt-6 px-4 space-y-1">
                <a href="?filter=active" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?php echo $filter === 'active' ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-phone-call text-lg"></i>
                        <span>Active Directory</span>
                    </div>
                </a>

                <a href="?filter=favorites" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?php echo $filter === 'favorites' ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-star text-lg text-amber-400 fill-amber-400/10"></i>
                        <span>Priority / Hotlines</span>
                    </div>
                </a>

                <a href="?filter=all" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?php echo $filter === 'all' ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-database text-lg"></i>
                        <span>All Slots (Inc. Blanks)</span>
                    </div>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 text-xs text-slate-500 text-center">
            &copy; 2026 Emergency DB Dashboard
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 bg-gray-100">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
            <form method="GET" action="" class="w-full max-w-lg flex items-center relative">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
                <i class="ph ph-magnifying-glass absolute left-3 text-gray-400 text-lg"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="<?php echo htmlspecialchars($search); ?>" 
                    placeholder="Search by name, number, keywords..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                >
                <?php if(!empty($search)): ?>
                    <a href="?filter=<?php echo $filter; ?>" class="absolute right-3 text-gray-400 hover:text-gray-600 text-sm">Clear</a>
                <?php endif; ?>
            </form>
            
            <div class="text-sm text-gray-500 font-medium">
                Found: <span class="bg-gray-200 text-gray-800 px-2 py-0.5 rounded-full font-bold"><?php echo count($contacts); ?></span> entries
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            
            <section class="w-1/2 flex flex-col bg-white border-r border-gray-200">
                <div class="overflow-y-auto flex-1 divide-y divide-gray-100">
                    <?php if (count($contacts) > 0): ?>
                        <?php foreach ($contacts as $contact): ?>
                            <?php 
                                $is_blank = (strpos($contact['name'], 'Blank Slot') === 0);
                                $is_active_selection = ($contact['id'] == $selected_id);
                            ?>
                            <a href="?id=<?php echo $contact['id']; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>" 
                               class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors block <?php echo $is_active_selection ? 'bg-blue-50/70 hover:bg-blue-50' : ''; ?>">
                                
                                <div class="flex items-center space-x-4 min-w-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 font-bold text-sm
                                        <?php echo $contact['is_favorite'] ? 'bg-amber-100 text-amber-700' : ($is_blank ? 'bg-gray-100 text-gray-400' : 'bg-blue-100 text-blue-700'); ?>">
                                        <?php 
                                            if ($contact['is_favorite'] && !$is_blank) {
                                                echo '<i class="ph-fill ph-star"></i>';
                                            } else {
                                                echo strtoupper(substr($contact['name'], 0, 2));
                                            }
                                        ?>
                                    </div>
                                    
                                    <div class="min-w-0">
                                        <h3 class="text-sm font-semibold truncate <?php echo $is_blank ? 'text-gray-400 italic' : 'text-gray-900'; ?>">
                                            <?php echo htmlspecialchars($contact['name']); ?>
                                        </h3>
                                        <p class="text-xs text-gray-500 font-mono mt-0.5"><?php echo htmlspecialchars($contact['number']); ?></p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <?php if(!$is_blank && !empty($contact['email'])): ?>
                                        <span class="text-gray-400 hover:text-gray-600 hidden md:inline"><i class="ph ph-envelope"></i></span>
                                    <?php endif; ?>
                                    <i class="ph ph-caret-right text-gray-400"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-8 text-center text-gray-500">
                            <i class="ph ph-smiley-blank text-4xl block mb-2 text-gray-300"></i>
                            No items match your criteria.
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="w-1/2 bg-gray-50 p-8 overflow-y-auto flex flex-col justify-start">
                <?php if ($selected_contact): ?>
                    <?php $is_blank_selected = (strpos($selected_contact['name'], 'Blank Slot') === 0); ?>
                    
                    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                        <div class="h-2 <?php echo $selected_contact['is_favorite'] ? 'bg-amber-400' : ($is_blank_selected ? 'bg-gray-300' : 'bg-blue-500'); ?>"></div>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($selected_contact['name']); ?></h2>
                                    <span class="inline-block mt-2 text-xs px-2 py-1 rounded-sm font-semibold uppercase tracking-wider <?php echo $selected_contact['is_favorite'] ? 'bg-amber-100 text-amber-800' : ($is_blank_selected ? 'bg-gray-100 text-gray-400' : 'bg-blue-100 text-blue-800'); ?>">
                                        <?php echo $selected_contact['is_favorite'] ? 'Priority Contact' : ($is_blank_selected ? 'Empty Slot' : 'Standard Directory'); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-4 border-t border-gray-100 pt-4">
                                <div>
                                    <label class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-1">Hotline / Phone Number</label>
                                    <div class="flex items-center gap-2 text-lg font-mono font-bold text-slate-800">
                                        <i class="ph ph-phone text-blue-500"></i>
                                        <span><?php echo htmlspecialchars($selected_contact['number']); ?></span>
                                    </div>
                                </div>

                                <?php if (!empty($selected_contact['email'])): ?>
                                <div>
                                    <label class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-1">Email Address</label>
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="ph ph-envelope text-gray-500"></i>
                                        <a href="mailto:<?php echo htmlspecialchars($selected_contact['email']); ?>" class="hover:underline text-blue-600">
                                            <?php echo htmlspecialchars($selected_contact['email']); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div>
                                    <label class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-1">Description / Notes</label>
                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100 leading-relaxed">
                                        <?php echo nl2br(htmlspecialchars($selected_contact['description'] ?: 'No description provided.')); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="h-full flex flex-col items-center justify-center text-gray-400">
                        <i class="ph ph-address-book text-6xl mb-2 text-gray-300"></i>
                        <p>Select a contact from the list view to preview parameters.</p>
                    </div>
                <?php endif; ?>
            </section>

        </div>
    </main>

</body>
</html>