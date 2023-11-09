<?php

echo $this->Form->create($productSearchForm, ['action' => '/wbsearch', 'method' => 'POST']);
echo $this->Form->control('query', ['label' => 'Введите фразу для поиска', 'value' => $userQuery ]);
echo $this->Form->button('Поиск');
echo $this->Form->end();

?>
<?php if (isset($products)) {?>
    <?php if (!empty($products)) {?>
        <ul class="pagination">
            <?php
            for ($page = 1; $page <= 10; $page++) { ?>
                <li>
                    <?php if ($page === $currentPage) {
                        ?><span><?php echo $page; ?></span>
                    <?php } else { ?>
                        <a href="/wbsearch?page=<?php echo $page; ?>&query=<?php echo $userQuery;?>">
                            <?php echo $page; ?>
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
        <table>
            <thead>
                <th>Позиция</th>
                <th>Название</th>
                <th>Бренд</th>
            </thead>
            <tbody>
            <?php foreach ($products as $product) {?>
                <tr>
                    <td><?php echo $product->getPosition(); ?></td>
                    <td><?php echo $product->getName(); ?></td>
                    <td><?php echo $product->getBrand(); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    <?php } else {?>
        <h4>Товаров не найдено.</h4>
    <?php } ?>
<?php } ?>
