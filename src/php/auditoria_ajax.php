<?php
/**
 * =====================================================
 * Endpoint AJAX para Carregar Histórico de Auditoria
 * Sistema de Login com PHP e MySQL
 * =====================================================
 */

include_once 'protect.php';
protect();

include_once 'config.php';

// Verifica se é admin
if ($_SESSION['nivel_acesso'] !== 'admin') {
    echo '<div class="alert alert-danger">Acesso negado.</div>';
    exit();
}

// Verifica se foi passado o ID do usuário
if (!isset($_GET['usuario_id'])) {
    echo '<div class="alert alert-danger">ID de usuário não fornecido.</div>';
    exit();
}

$usuario_id = (int)$_GET['usuario_id'];

// Busca os registros de auditoria
$sql = "SELECT data_hora, ip_origem, navegador, sistema_operacional, dispositivo, sucesso 
        FROM auditoria_login 
        WHERE usuario_id = ? 
        ORDER BY data_hora DESC 
        LIMIT 50";

$stmt = $link->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

$stmt->close();
$link->close();

if (count($registros) > 0): ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr>
                    <th><i class="fas fa-clock me-1"></i>Data/Hora</th>
                    <th><i class="fas fa-globe me-1"></i>IP</th>
                    <th><i class="fas fa-desktop me-1"></i>Dispositivo</th>
                    <th><i class="fas fa-laptop me-1"></i>Sistema</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro): ?>
                <tr>
                    <td>
                        <small><?php echo date('d/m/Y H:i:s', strtotime($registro['data_hora'])); ?></small>
                    </td>
                    <td>
                        <span class="badge bg-secondary">
                            <?php echo htmlspecialchars($registro['ip_origem']); ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        $dispositivo_icon = [
                            'Desktop' => 'desktop',
                            'Mobile' => 'mobile-alt',
                            'Tablet' => 'tablet-alt'
                        ];
                        $icon = $dispositivo_icon[$registro['dispositivo']] ?? 'desktop';
                        ?>
                        <i class="fas fa-<?php echo $icon; ?> me-1"></i>
                        <?php echo htmlspecialchars($registro['dispositivo']); ?>
                    </td>
                    <td>
                        <?php 
                        $so_icon = [
                            'Windows' => 'windows',
                            'macOS' => 'apple',
                            'Linux' => 'linux',
                            'Android' => 'android',
                            'iOS' => 'apple'
                        ];
                        $icon = $so_icon[$registro['sistema_operacional']] ?? 'laptop';
                        ?>
                        <i class="fab fa-<?php echo $icon; ?> me-1"></i>
                        <?php echo htmlspecialchars($registro['sistema_operacional'] ?: 'Desconhecido'); ?>
                    </td>
                </tr>
                <tr class="table-light">
                    <td colspan="4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Navegador:</strong> <?php echo htmlspecialchars(substr($registro['navegador'], 0, 100)); ?>
                            <?php if (strlen($registro['navegador']) > 100): ?>...<?php endif; ?>
                        </small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="alert alert-info mb-0">
        <i class="fas fa-info-circle me-2"></i>
        <small>Exibindo os últimos 50 acessos deste usuário.</small>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Nenhum registro de acesso encontrado para este usuário.
    </div>
<?php endif; ?>
