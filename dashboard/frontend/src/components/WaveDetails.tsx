import { useState, useEffect, useCallback } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { api, WaveDetails as WaveDetailsType } from '../api/client';
import { getStatusConfig } from '../utils/status';
import { formatDate, formatUUID } from '../utils/format';
import './common.css';
import './WaveDetails.css';

export default function WaveDetails() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [details, setDetails] = useState<WaveDetailsType | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [restarting, setRestarting] = useState<string | null>(null);
  const [showLogs, setShowLogs] = useState<string | null>(null);
  const [logs, setLogs] = useState<string | null>(null);
  const [loadingLogs, setLoadingLogs] = useState(false);
  const [removingLock, setRemovingLock] = useState<string | null>(null);

  useEffect(() => {
    if (id) {
      loadDetails();
      // Обновляем статус каждые 5 секунд если волна в процессе
      const interval = setInterval(() => {
        if (details?.wave.status === 'in_progress' || details?.wave.status === 'pending') {
          loadDetails();
        }
      }, 5000);
      return () => clearInterval(interval);
    }
  }, [id, details?.wave.status]);

  const loadDetails = async () => {
    if (!id) return;
    try {
      setLoading(true);
      setError(null);
      const response = await api.getWaveDetails(id);
      if (response.success && response.data) {
        setDetails(response.data);
      } else {
        setError(response.error || 'Волна не найдена');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка загрузки деталей');
    } finally {
      setLoading(false);
    }
  };

  const handleRestartMigration = async (mbUuid: string) => {
    if (!id) return;
    try {
      setRestarting(mbUuid);
      setError(null);
      
      const response = await api.restartWaveMigration(id, mbUuid);
      
      if (response.success) {
        // Перезагружаем детали
        await loadDetails();
      } else {
        setError(response.error || 'Ошибка перезапуска миграции');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка перезапуска миграции');
    } finally {
      setRestarting(null);
    }
  };

  const handleRemoveLock = async (mbUuid: string) => {
    if (!id) return;
    
    if (!confirm('Вы уверены, что хотите удалить lock-файл? Это разблокирует миграцию для повторного запуска.')) {
      return;
    }
    
    try {
      setRemovingLock(mbUuid);
      setError(null);
      
      const response = await api.removeWaveMigrationLock(id, mbUuid);
      
      if (response.success) {
        const message = (response.data as any)?.message || 'Lock-файл успешно удален';
        alert(message);
        // Перезагружаем детали
        await loadDetails();
      } else {
        setError(response.error || 'Ошибка удаления lock-файла');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка удаления lock-файла');
    } finally {
      setRemovingLock(null);
    }
  };

  const loadLogs = useCallback(async (mbUuid: string) => {
    if (!id) return;
    
    try {
      setLoadingLogs(true);
      
      const response = await api.getWaveMigrationLogs(id, mbUuid);
      
      if (response.success && response.data) {
        let logText = '';
        
        // Обрабатываем разные форматы ответа
        if (Array.isArray(response.data.logs)) {
          // Если это массив строк, объединяем их
          logText = response.data.logs
            .filter((line: string) => line && line.trim()) // Убираем пустые строки
            .join('\n');
        } else if (typeof response.data.logs === 'string') {
          logText = response.data.logs;
        } else if (typeof response.data === 'string') {
          logText = response.data;
        } else if (response.data.logs && typeof response.data.logs === 'object') {
          // Если logs это объект, преобразуем в строку
          logText = JSON.stringify(response.data.logs, null, 2);
        } else {
          logText = JSON.stringify(response.data, null, 2);
        }
        
        // Нормализуем переносы строк (унифицируем \r\n и \r в \n)
        logText = logText.replace(/\r\n/g, '\n').replace(/\r/g, '\n');
        
        // Разбиваем логи по паттерну начала новой записи
        // Паттерн: ][ или начало с [202 (дата в формате [YYYY-MM-DD)
        // Заменяем ][ на ]\n[ чтобы каждая запись была на новой строке
        logText = logText.replace(/\]\[/g, ']\n[');
        
        // Также разбиваем по паттерну начала новой записи [202
        logText = logText.replace(/(\])(\[202)/g, '$1\n$2');
        
        setLogs(logText || 'Логи не найдены');
      } else {
        setLogs('Логи не найдены');
      }
    } catch (err: any) {
      setLogs('Ошибка загрузки логов: ' + err.message);
    } finally {
      setLoadingLogs(false);
    }
  }, [id]);

  const handleShowLogs = async (mbUuid: string) => {
    if (!id) return;
    
    if (showLogs === mbUuid) {
      setShowLogs(null);
      setLogs(null);
      return;
    }

    setShowLogs(mbUuid);
    await loadLogs(mbUuid);
  };

  // Автообновление логов для миграций в процессе
  useEffect(() => {
    if (!showLogs || !id) return;
    
    const migration = details?.migrations.find(m => m.mb_project_uuid === showLogs);
    if (migration?.status === 'in_progress') {
      const interval = setInterval(() => {
        loadLogs(showLogs);
      }, 3000); // Обновляем каждые 3 секунды
      
      return () => clearInterval(interval);
    }
  }, [showLogs, details?.migrations, id, loadLogs]);

  if (loading && !details) {
    return (
      <div className="loading-container">
        <div className="spinner"></div>
        <p>Загрузка деталей волны...</p>
      </div>
    );
  }

  if (error && !details) {
    return (
      <div className="error-container">
        <p className="error-message">❌ {error}</p>
        <button onClick={() => navigate('/wave')} className="btn btn-primary">
          Вернуться к списку
        </button>
      </div>
    );
  }

  if (!details) {
    return null;
  }

  const wave = details.wave;
  const statusConfig = getStatusConfig(wave.status as any);
  const progressPercent = wave.progress.total > 0
    ? Math.round((wave.progress.completed / wave.progress.total) * 100)
    : 0;

  return (
    <div className="wave-details">
      <div className="page-header">
        <button onClick={() => navigate('/wave')} className="btn btn-secondary">
          ← Назад
        </button>
        <h2>Волна: {wave.name}</h2>
        <div>
          <span
            className="status-badge"
            style={{
              color: statusConfig.color,
              backgroundColor: statusConfig.bgColor,
            }}
          >
            {statusConfig.label}
          </span>
        </div>
      </div>

      {error && (
        <div className="alert alert-error">
          {error}
        </div>
      )}

      <div className="details-grid">
        <div className="card">
          <div className="card-header">
            <h3 className="card-title">Информация о волне</h3>
          </div>
          <div className="info-grid">
            <div className="info-item">
              <span className="info-label">Название:</span>
              <span className="info-value">{wave.name}</span>
            </div>
            <div className="info-item">
              <span className="info-label">Workspace:</span>
              <span className="info-value">{wave.workspace_name} (ID: {wave.workspace_id})</span>
            </div>
            <div className="info-item">
              <span className="info-label">Статус:</span>
              <span className="info-value">
                <span
                  className="status-badge"
                  style={{
                    color: statusConfig.color,
                    backgroundColor: statusConfig.bgColor,
                  }}
                >
                  {statusConfig.label}
                </span>
              </span>
            </div>
            <div className="info-item">
              <span className="info-label">Прогресс:</span>
              <span className="info-value">
                {wave.progress.completed} / {wave.progress.total}
                {wave.progress.failed > 0 && (
                  <span style={{ color: '#ef4444', marginLeft: '0.5rem' }}>
                    ({wave.progress.failed} ошибок)
                  </span>
                )}
              </span>
            </div>
            <div className="info-item">
              <span className="info-label">Прогресс:</span>
              <span className="info-value">
                <div className="progress-bar" style={{ width: '200px' }}>
                  <div
                    className="progress-fill"
                    style={{
                      width: `${progressPercent}%`,
                      backgroundColor: wave.progress.failed > 0 ? '#ef4444' : '#10b981',
                    }}
                  />
                </div>
                <span style={{ marginLeft: '0.5rem' }}>{progressPercent}%</span>
              </span>
            </div>
            <div className="info-item">
              <span className="info-label">Создано:</span>
              <span className="info-value">{formatDate(wave.created_at)}</span>
            </div>
            {wave.completed_at && (
              <div className="info-item">
                <span className="info-label">Завершено:</span>
                <span className="info-value">{formatDate(wave.completed_at)}</span>
              </div>
            )}
            <div className="info-item">
              <span className="info-label">Действия:</span>
              <span className="info-value">
                <Link
                  to={`/wave/${id}/mapping`}
                  className="btn btn-primary"
                  style={{ marginTop: '0.5rem' }}
                >
                  📋 Маппинг
                </Link>
              </span>
            </div>
          </div>
        </div>

        <div className="card">
          <div className="card-header">
            <h3 className="card-title">Миграции в волне</h3>
          </div>
          {details.migrations.length === 0 ? (
            <p className="empty-message">Миграции еще не начаты</p>
          ) : (
            <div className="migrations-table-container">
              <table className="migrations-table">
                <thead>
                  <tr>
                    <th>MB Project UUID</th>
                    <th>Brizy Project ID</th>
                    <th>Статус</th>
                    <th>Domain</th>
                    <th>Прогресс</th>
                    <th>Дата</th>
                    <th>Действия</th>
                  </tr>
                </thead>
                <tbody>
                  {details.migrations.map((migration, index) => {
                    const migrationStatusConfig = getStatusConfig(migration.status as any);
                    const progress = migration.result_data?.progress;
                    return (
                      <tr key={migration.mb_project_uuid || index}>
                        <td className="uuid-cell">{formatUUID(migration.mb_project_uuid)}</td>
                        <td>
                          {migration.brz_project_id ? (
                            <Link
                              to={`/migrations/${migration.brz_project_id}`}
                              className="link"
                            >
                              {migration.brz_project_id}
                            </Link>
                          ) : (
                            '-'
                          )}
                        </td>
                        <td>
                          <span
                            className="status-badge"
                            style={{
                              color: migrationStatusConfig.color,
                              backgroundColor: migrationStatusConfig.bgColor,
                            }}
                          >
                            {migrationStatusConfig.label}
                          </span>
                          {migration.error && (
                            <div className="error-text" style={{ fontSize: '0.75rem', marginTop: '0.25rem' }}>
                              {migration.error}
                            </div>
                          )}
                          {migration.result_data?.warnings && migration.result_data.warnings.length > 0 && (
                            <div className="warning-text" style={{ fontSize: '0.75rem', marginTop: '0.25rem', color: '#856404' }}>
                              ⚠ {migration.result_data.warnings.length} предупреждений
                            </div>
                          )}
                        </td>
                        <td>
                          {migration.brizy_project_domain ? (
                            <a
                              href={migration.brizy_project_domain}
                              target="_blank"
                              rel="noopener noreferrer"
                              className="link"
                            >
                              {migration.brizy_project_domain}
                            </a>
                          ) : (
                            '-'
                          )}
                        </td>
                        <td>
                          {progress ? (
                            <div className="progress-info-small">
                              <span>
                                {progress.Success || 0} / {progress.Total || 0}
                              </span>
                              {progress.processTime && (
                                <span style={{ fontSize: '0.75rem', color: '#666', display: 'block' }}>
                                  {progress.processTime.toFixed(1)}s
                                </span>
                              )}
                            </div>
                          ) : (
                            '-'
                          )}
                        </td>
                        <td>
                          {migration.completed_at ? formatDate(migration.completed_at) : '-'}
                        </td>
                        <td>
                          <div className="action-buttons">
                            {migration.brz_project_id && (
                              <>
                                <button
                                  onClick={() => handleRestartMigration(migration.mb_project_uuid)}
                                  className="btn btn-sm btn-primary"
                                  disabled={restarting === migration.mb_project_uuid}
                                  title="Перезапустить миграцию"
                                >
                                  {restarting === migration.mb_project_uuid ? '...' : '↻'}
                                </button>
                                <button
                                  onClick={() => handleRemoveLock(migration.mb_project_uuid)}
                                  className="btn btn-sm"
                                  disabled={removingLock === migration.mb_project_uuid}
                                  title="Удалить lock-файл (разблокировать миграцию)"
                                  style={{ 
                                    backgroundColor: '#f59e0b', 
                                    color: '#fff', 
                                    borderColor: '#f59e0b',
                                    marginLeft: '0.25rem'
                                  }}
                                >
                                  {removingLock === migration.mb_project_uuid ? '...' : '🔓'}
                                </button>
                                <button
                                  onClick={() => handleShowLogs(migration.mb_project_uuid)}
                                  className="btn btn-sm btn-secondary"
                                  title="Показать логи"
                                >
                                  📋
                                </button>
                                <Link
                                  to={`/migrations/${migration.brz_project_id}`}
                                  className="btn btn-sm btn-link"
                                  title="Детали миграции"
                                >
                                  👁
                                </Link>
                              </>
                            )}
                          </div>
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
          )}

          {showLogs && (
            <div className="logs-section">
              <div className="logs-header">
                <h4>
                  Логи миграции
                  {details?.migrations.find(m => m.mb_project_uuid === showLogs)?.status === 'in_progress' && (
                    <span className="auto-refresh-badge">🔄 Автообновление</span>
                  )}
                </h4>
                <div>
                  <button
                    onClick={() => loadLogs(showLogs)}
                    className="btn btn-sm btn-secondary"
                    title="Обновить логи"
                    disabled={loadingLogs}
                  >
                    {loadingLogs ? '...' : '↻'}
                  </button>
                  <button
                    onClick={() => {
                      setShowLogs(null);
                      setLogs(null);
                    }}
                    className="btn btn-sm btn-secondary"
                    style={{ marginLeft: '0.5rem' }}
                  >
                    ✕
                  </button>
                </div>
              </div>
              {loadingLogs && !logs ? (
                <div className="loading-container">
                  <div className="spinner"></div>
                  <p>Загрузка логов...</p>
                </div>
              ) : (
                <div className="logs-content">
                  {logs ? (
                    <div className="logs-text">
                      {logs
                        .split('\n')
                        .filter(line => line.trim()) // Убираем полностью пустые строки
                        .map((line, index) => {
                          // Определяем тип строки для стилизации
                          let lineClass = 'log-line';
                          const trimmedLine = line.trim();
                          const lowerLine = trimmedLine.toLowerCase();
                          
                          // Проверяем уровень лога по паттерну Monolog: .INFO:, .ERROR:, .CRITICAL:, .WARNING:, .DEBUG:
                          if (/\.[CRITICAL|ERROR|FATAL]:/i.test(trimmedLine) ||
                              lowerLine.includes('.critical:') ||
                              lowerLine.includes('.error:') ||
                              lowerLine.includes('.fatal:')) {
                            lineClass += ' log-error';
                          } 
                          // Проверяем на предупреждения
                          else if (/\.[WARNING|WARN]:/i.test(trimmedLine) ||
                                   lowerLine.includes('.warning:') ||
                                   lowerLine.includes('.warn:')) {
                            lineClass += ' log-warning';
                          } 
                          // Проверяем на информационные сообщения
                          else if (/\.[INFO|SUCCESS]:/i.test(trimmedLine) ||
                                   lowerLine.includes('.info:') ||
                                   lowerLine.includes('.success:') ||
                                   lowerLine.includes('completed') ||
                                   lowerLine.includes('done')) {
                            lineClass += ' log-info';
                          } 
                          // Проверяем на отладочные сообщения
                          else if (/\.[DEBUG|TRACE]:/i.test(trimmedLine) ||
                                   lowerLine.includes('.debug:') ||
                                   lowerLine.includes('.trace:')) {
                            lineClass += ' log-debug';
                          }
                          // Дополнительные проверки для общих слов
                          else if (lowerLine.includes('error') || 
                                   lowerLine.includes('exception') || 
                                   lowerLine.includes('failed') ||
                                   lowerLine.includes('critical')) {
                            lineClass += ' log-error';
                          } 
                          else if (lowerLine.includes('warning') || 
                                   lowerLine.includes('warn') ||
                                   lowerLine.includes('deprecated')) {
                            lineClass += ' log-warning';
                          }
                          
                          return (
                            <div key={`log-${index}`} className={lineClass}>
                              <span className="log-line-content">{line || '\u00A0'}</span>
                            </div>
                          );
                        })}
                    </div>
                  ) : (
                    <div className="logs-empty">Логи не найдены</div>
                  )}
                </div>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
