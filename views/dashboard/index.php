<?php include_once __DIR__.'/header-dashboard.php'; ?>

    <?php if(count($proyectos) === 0): ?>
        <p class="no-proyectos">No tienes ningún proyecto creado, <a href="/crear-proyecto">crea uno</a></p>
    <?php else: ?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto): ?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?=$proyecto->url;?>"><?=$proyecto->proyecto;?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php include_once __DIR__.'/footer-dashboard.php'; ?>