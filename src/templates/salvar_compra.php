<?php if (isset($compras)){?>
    <ul>
    <?php foreach($compras as $compra) {?>
        <li><?php echo $compra["nombre"];?></li>
    <?php } ?>
    </ul>
<?php } ?>

<form action="<?php $url ?>" method="POST" content="application/x-www-form-urlencoded" >
    <label>Nombre:</label>
    <input type="text" name="nombre"/>
    <label>Precio:</label>
    <input type="text" name="precio"/>
    <input type="submit">
</form>
