<section>
    <h2>Dashboard</h2>
    <p>Back-office plateforme opérationnel.</p>

    <ul>
        <li>Base de données : <?= e($stats['database'] ?? 'inconnue') ?></li>
        <li>Module actif : <?= e($stats['module'] ?? 'dashboard') ?></li>
    </ul>
</section>
