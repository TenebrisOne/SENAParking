<?php
/**
 * Guardar Metadata de Baseline
 * Guarda información sobre cuándo y en qué estado se creó la baseline
 */

$baselineDir = __DIR__ . '/baselines/current/';

$metadata = [
    'timestamp' => date('Y-m-d H:i:s'),
    'git_branch' => exec('git rev-parse --abbrev-ref HEAD 2>nul'),
    'git_commit' => exec('git rev-parse HEAD 2>nul'),
    'git_commit_short' => exec('git rev-parse --short HEAD 2>nul'),
    'php_version' => PHP_VERSION,
    'phpunit_version' => exec('vendor\\bin\\phpunit --version 2>nul'),
    'user' => get_current_user(),
    'hostname' => gethostname()
];

// Contar tests en cada suite
$metadata['test_counts'] = [
    'unit' => countTestsInXml($baselineDir . 'unit-baseline.xml'),
    'integration' => countTestsInXml($baselineDir . 'integration-baseline.xml'),
    'e2e' => countTestsInXml($baselineDir . 'e2e-baseline.xml')
];

$metadata['total_tests'] = array_sum($metadata['test_counts']);

// Guardar metadata
file_put_contents(
    $baselineDir . 'metadata.json',
    json_encode($metadata, JSON_PRETTY_PRINT)
);

echo "✅ Metadata guardada\n";
echo "   Timestamp: " . $metadata['timestamp'] . "\n";
echo "   Total tests: " . $metadata['total_tests'] . "\n";
if ($metadata['git_commit_short']) {
    echo "   Git commit: " . $metadata['git_commit_short'] . "\n";
}

function countTestsInXml($file) {
    if (!file_exists($file)) {
        return 0;
    }
    
    $xml = @simplexml_load_file($file);
    if (!$xml) {
        return 0;
    }
    
    $count = 0;
    foreach ($xml->xpath('//testcase') as $test) {
        $count++;
    }
    
    return $count;
}
