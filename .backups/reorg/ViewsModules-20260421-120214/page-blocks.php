<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h5 mb-1">Blocs de page</h2>
            <div class="text-muted"><?= e($page['title'] ?? '') ?> (<?= e($page['slug'] ?? '') ?>)</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="/admin.php?module=pages&action=edit&id=<?= (int)$page['id'] ?>">Modifier la page</a>
            <a class="btn btn-outline-secondary" href="/admin.php?module=pages">Retour aux pages</a>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Ajouter un bloc</h3>
                <p class="text-muted small mb-4">Choisis un type de bloc. Tu pourras ensuite le remplir proprement dans l’étape suivante.</p>

                <div class="d-grid gap-2">
                    <form method="post" action="/admin.php?module=pages&action=add_block&id=<?= (int)$page['id'] ?>">
                        <input type="hidden" name="block_type" value="hero">
                        <input type="hidden" name="sort_order" value="<?= count($blocks) ?>">
                        <button class="btn btn-outline-secondary w-100 text-start" type="submit">Ajouter un Hero</button>
                    </form>

                    <form method="post" action="/admin.php?module=pages&action=add_block&id=<?= (int)$page['id'] ?>">
                        <input type="hidden" name="block_type" value="rich-text">
                        <input type="hidden" name="sort_order" value="<?= count($blocks) + 1 ?>">
                        <button class="btn btn-outline-secondary w-100 text-start" type="submit">Ajouter un texte riche</button>
                    </form>

                    <form method="post" action="/admin.php?module=pages&action=add_block&id=<?= (int)$page['id'] ?>">
                        <input type="hidden" name="block_type" value="menu">
                        <input type="hidden" name="sort_order" value="<?= count($blocks) + 2 ?>">
                        <button class="btn btn-outline-secondary w-100 text-start" type="submit">Ajouter un menu</button>
                    </form>

                    <form method="post" action="/admin.php?module=pages&action=add_block&id=<?= (int)$page['id'] ?>">
                        <input type="hidden" name="block_type" value="cta">
                        <input type="hidden" name="sort_order" value="<?= count($blocks) + 3 ?>">
                        <button class="btn btn-outline-secondary w-100 text-start" type="submit">Ajouter un CTA</button>
                    </form>

                    <form method="post" action="/admin.php?module=pages&action=add_block&id=<?= (int)$page['id'] ?>">
                        <input type="hidden" name="block_type" value="posts-list">
                        <input type="hidden" name="sort_order" value="<?= count($blocks) + 4 ?>">
                        <button class="btn btn-outline-secondary w-100 text-start" type="submit">Ajouter une liste d’articles</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="h6 mb-3">Blocs existants</h3>

                <?php if (empty($blocks)): ?>
                    <div class="text-muted">Aucun bloc sur cette page.</div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($blocks as $index => $block): ?>
                            <?php
                            $settingsDecoded = json_decode($block['settings_json'] ?? '{}', true) ?: [];
                            $summary = '';

                            if (($block['block_type'] ?? '') === 'hero') {
                                $summary = $settingsDecoded['title'] ?? 'Hero vide';
                            } elseif (($block['block_type'] ?? '') === 'rich-text') {
                                $summary = $settingsDecoded['title'] ?? 'Bloc texte';
                            } elseif (($block['block_type'] ?? '') === 'menu') {
                                $summary = 'Emplacement : ' . ($settingsDecoded['menu_location'] ?? 'main');
                            } elseif (($block['block_type'] ?? '') === 'cta') {
                                $summary = $settingsDecoded['title'] ?? 'CTA';
                            } elseif (($block['block_type'] ?? '') === 'posts-list') {
                                $summary = ($settingsDecoded['title'] ?? 'Liste d’articles') . ' • ' . (($settingsDecoded['limit'] ?? 3)) . ' article(s)';
                            }
                            ?>
                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">
                                        #<?= (int)$block['id'] ?> • <?= e($block['block_type']) ?>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        Ordre : <?= (int)($block['sort_order'] ?? 0) ?>
                                    </div>
                                    <div class="mt-2">
                                        <?= e($summary) ?>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 flex-wrap justify-content-end">
                                    <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=pages&action=move_block_up&id=<?= (int)$page['id'] ?>&block_id=<?= (int)$block['id'] ?>">↑</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=pages&action=move_block_down&id=<?= (int)$page['id'] ?>&block_id=<?= (int)$block['id'] ?>">↓</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="/admin.php?module=pages&action=edit_block&id=<?= (int)$page['id'] ?>&block_id=<?= (int)$block['id'] ?>">Configurer</a>
                                    <a class="btn btn-sm btn-outline-danger" href="/admin.php?module=pages&action=delete_block&id=<?= (int)$page['id'] ?>&block_id=<?= (int)$block['id'] ?>" onclick="return confirm('Supprimer ce bloc ?')">Supprimer</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
