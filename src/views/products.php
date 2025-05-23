<h1>Product list</h1>
<ul>
    <?php foreach ($products as $product): ?>
        <li><?= htmlspecialchars($product) ?></li>
    <?php endforeach; ?>
</ul>
