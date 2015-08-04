<section class="section">
	<article class="article large">
		<h2>Éditer une page</h2>
		<form action="brouillon.php" methode="GET">
		<input type="text" name="title" placeholder="Titre" class="input">
    	<textarea id="editor" name="content" placeholder="Écrivez votre article ici"></textarea>
		<hr>
		<h2>Référencement</h2>
		<input type="text" name="motcles" placeholder="Mots clés" class="input">
		<textarea name="description" placeholder="Description"></textarea>
		<input type="text" name="titlemeta" placeholder="Titre métadonnée" class="input">
		</form>
	</article>
	<article class="article small">
		<h2>Publier</h2>
		<form action="inc/brouillon.php" methode="GET">
		<button class="btn grey" type="submit" style="float:none">Enregistrer en tant que brouillon</button>
		</form>
		<hr>
		<form action="inc/registred.php" methode="GET">
		<button class="btn" type="submit">Publier</button>
		</form>
	</article>
	<article class="article small" style="margin-top:22px;">
		<h2>Catégories</h2>
		<div class="tabs-panel">
		<input type="checkbox" class="category" id="category1"><label for="category1" class="category">Catégorie 1</label>
		</div>
		<div class="tabs-panel">
		<input type="checkbox" class="category" id="category2"><label for="category2" class="category">Catégorie 2</label>
		</div>
		<div class="tabs-panel">
		<input type="checkbox" class="category" id="category3"><label for="category3" class="category">Catégorie 3</label>
		</div>
		<div class="tabs-panel">
		<input type="checkbox" class="category" id="category4"><label for="category4" class="category">Catégorie 4</label>
		</div>
		<div class="tabs-panel">
		<input type="checkbox" class="category" id="category5"><label for="category5" class="category">Catégorie 5</label>
		</div>
		<div class="tabs-panel">
		<input type="checkbox" class="category" id="category6"><label for="category6" class="category">Catégorie 6</label>
		</div>
		<div class="tabs-panel">
		<input type="checkbox" class="category" id="category7"><label for="category7" class="category">Catégorie 7</label>
		</div>
	</article>
</section>
