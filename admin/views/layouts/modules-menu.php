<?php if (isModuleEnabled($settings, 'products')): ?>
<a href="/?module=products" class="list-group-item list-group-item-action">Produits</a>
<?php endif; ?>

<?php if (isModuleEnabled($settings, 'forms')): ?>
<a href="/?module=forms" class="list-group-item list-group-item-action">Formulaires</a>
<?php endif; ?>

<?php if (isModuleEnabled($settings, 'booking')): ?>
<a href="/?module=booking" class="list-group-item list-group-item-action">Réservations</a>
<?php endif; ?>

<?php if (isModuleEnabled($settings, 'testimonials')): ?>
<a href="/?module=testimonials" class="list-group-item list-group-item-action">Avis</a>
<?php endif; ?>

<?php if (isModuleEnabled($settings, 'gallery')): ?>
<a href="/?module=gallery" class="list-group-item list-group-item-action">Galerie</a>
<?php endif; ?>
