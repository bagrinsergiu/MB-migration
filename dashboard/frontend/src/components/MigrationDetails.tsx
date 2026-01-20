import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { api, MigrationDetails as MigrationDetailsType, QualityStatistics } from '../api/client';
import { getStatusConfig } from '../utils/status';
import { formatDate, formatUUID } from '../utils/format';
import QualityAnalysis from './QualityAnalysis';
import './MigrationDetails.css';
import './common.css';

export default function MigrationDetails() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [details, setDetails] = useState<MigrationDetailsType | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [restarting, setRestarting] = useState(false);
  const [showRestartForm, setShowRestartForm] = useState(false);
  const [restartParams, setRestartParams] = useState({
    mb_site_id: '',
    mb_secret: '',
    brz_workspaces_id: '',
    mb_page_slug: '',
    mgr_manual: '0',
    quality_analysis: false,
  });
  const [defaultSettings, setDefaultSettings] = useState<{ mb_site_id?: number; mb_secret?: string }>({});
  const [activeTab, setActiveTab] = useState<'details' | 'analysis' | 'archive'>('details');
  const [qualityStatistics, setQualityStatistics] = useState<QualityStatistics | null>(null);

  useEffect(() => {
    // Загружаем настройки по умолчанию
    api.getSettings().then((response) => {
      if (response.success && response.data) {
        setDefaultSettings({
          mb_site_id: response.data.mb_site_id || undefined,
          mb_secret: response.data.mb_secret || undefined,
        });
      }
    }).catch((err) => {
      console.error('Ошибка загрузки настроек:', err);
    });
  }, []);

  useEffect(() => {
    if (id) {
      loadDetails();
      loadQualityStatistics();
    }
  }, [id]);

  const loadQualityStatistics = async () => {
    if (!id) return;
    try {
      const response = await api.getQualityStatistics(parseInt(id));
      if (response.success && response.data) {
        setQualityStatistics(response.data);
      }
    } catch (err) {
      // Игнорируем ошибки - статистика опциональна
      console.error('Error loading quality statistics:', err);
    }
  };

  useEffect(() => {
    // Обновляем статус каждые 5 секунд если миграция в процессе
    if (details?.status === 'in_progress') {
      const interval = setInterval(() => {
        loadDetails();
      }, 5000);
      return () => clearInterval(interval);
    }
  }, [details?.status]);

  const loadDetails = async () => {
    if (!id) return;
    try {
      setLoading(true);
      setError(null);
      const response = await api.getMigrationDetails(parseInt(id));
      if (response.success && response.data) {
        setDetails(response.data);
      } else {
        setError(response.error || 'Миграция не найдена');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка загрузки деталей');
    } finally {
      setLoading(false);
    }
  };

  const handleRestart = async () => {
    if (!id) return;
    try {
      setRestarting(true);
      const params: any = {};
      // Используем значения из формы, если они заданы, иначе из настроек по умолчанию
      if (restartParams.mb_site_id) {
        params.mb_site_id = parseInt(restartParams.mb_site_id);
      } else if (defaultSettings.mb_site_id) {
        params.mb_site_id = defaultSettings.mb_site_id;
      }
      if (restartParams.mb_secret) {
        params.mb_secret = restartParams.mb_secret;
      } else if (defaultSettings.mb_secret) {
        params.mb_secret = defaultSettings.mb_secret;
      }
      if (restartParams.brz_workspaces_id) params.brz_workspaces_id = parseInt(restartParams.brz_workspaces_id);
      if (restartParams.mb_page_slug) params.mb_page_slug = restartParams.mb_page_slug;
      if (restartParams.mgr_manual) params.mgr_manual = parseInt(restartParams.mgr_manual);
      if (restartParams.quality_analysis !== undefined) {
        params.quality_analysis = restartParams.quality_analysis;
      }

      const response = await api.restartMigration(parseInt(id), params);
      if (response.success) {
        setShowRestartForm(false);
        loadDetails();
      } else {
        setError(response.error || 'Ошибка перезапуска');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка перезапуска');
    } finally {
      setRestarting(false);
    }
  };

  if (loading) {
    return (
      <div className="loading-container">
        <div className="spinner"></div>
        <p>Загрузка деталей миграции...</p>
      </div>
    );
  }

  if (error && !details) {
    return (
      <div className="error-container">
        <p className="error-message">❌ {error}</p>
        <button onClick={() => navigate('/')} className="btn btn-primary">
          Вернуться к списку
        </button>
      </div>
    );
  }

  if (!details) {
    return null;
  }

  const statusConfig = getStatusConfig(details.status);
  
  // Безопасный парсинг result_json
  let resultData = null;
  if (details.result?.result_json) {
    try {
      resultData = typeof details.result.result_json === 'string'
        ? JSON.parse(details.result.result_json)
        : details.result.result_json;
    } catch (e) {
      console.error('Error parsing result_json:', e);
      resultData = null;
    }
  }
  
  // Извлекаем данные из value, если они там находятся, или используем result_data из API
  const migrationValue = (details as any).result_data || resultData?.value || resultData;
  
  // Безопасный парсинг changes_json
  let changesJson = null;
  if (details.mapping.changes_json) {
    try {
      const rawValue = typeof details.mapping.changes_json === 'string' 
        ? details.mapping.changes_json 
        : JSON.stringify(details.mapping.changes_json);
      
      // Проверяем, не обрезан ли JSON (неполная строка)
      if (rawValue.length > 0 && !rawValue.trim().endsWith('}') && !rawValue.trim().endsWith(']')) {
        // JSON обрезан - пытаемся восстановить или используем null
        console.warn('changes_json appears to be truncated, skipping parse');
        changesJson = null;
      } else {
        changesJson = typeof details.mapping.changes_json === 'string'
          ? JSON.parse(rawValue)
          : details.mapping.changes_json;
      }
    } catch (e) {
      // Не логируем ошибку в консоль, чтобы не засорять её
      // Просто используем null
      changesJson = null;
    }
  }
  
  // Если migrationValue пуст, но есть changes_json с данными, используем их
  if (!migrationValue && changesJson) {
    // Можно использовать данные из changes_json как fallback
  }

  return (
    <div className="migration-details">
      <div className="page-header">
        <button onClick={() => navigate('/')} className="btn btn-secondary">
          ← Назад
        </button>
        <h2>Детали миграции #{details.mapping.brz_project_id}</h2>
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

      <div className="migration-tabs">
        <button
          className={activeTab === 'details' ? 'active' : ''}
          onClick={() => setActiveTab('details')}
        >
          Детали
        </button>
        <button
          className={activeTab === 'analysis' ? 'active' : ''}
          onClick={() => setActiveTab('analysis')}
        >
          Анализ
        </button>
        <button
          className={activeTab === 'archive' ? 'active' : ''}
          onClick={() => setActiveTab('archive')}
        >
          Архив
        </button>
      </div>

      {activeTab === 'details' && (
        <div className="details-grid">
        {qualityStatistics && qualityStatistics.token_statistics && (
          <div className="card highlight-card">
            <div className="card-header">
              <h3 className="card-title">💰 Статистика анализа качества</h3>
            </div>
            <div className="info-grid">
              <div className="info-item">
                <span className="info-label">Общая стоимость анализа:</span>
                <span className="info-value" style={{ color: '#198754', fontWeight: 'bold', fontSize: '1.2em' }}>
                  ${qualityStatistics.token_statistics.total_cost_usd.toFixed(6)}
                </span>
              </div>
              <div className="info-item">
                <span className="info-label">Средняя стоимость на страницу:</span>
                <span className="info-value">
                  ${qualityStatistics.token_statistics.avg_cost_per_page_usd.toFixed(6)}
                </span>
              </div>
              <div className="info-item">
                <span className="info-label">Всего токенов использовано:</span>
                <span className="info-value">
                  {qualityStatistics.token_statistics.total_tokens.toLocaleString()}
                </span>
              </div>
              <div className="info-item">
                <span className="info-label">Входные токены:</span>
                <span className="info-value">
                  {qualityStatistics.token_statistics.total_prompt_tokens.toLocaleString()}
                </span>
              </div>
              <div className="info-item">
                <span className="info-label">Выходные токены:</span>
                <span className="info-value">
                  {qualityStatistics.token_statistics.total_completion_tokens.toLocaleString()}
                </span>
              </div>
              <div className="info-item">
                <span className="info-label">Среднее токенов на страницу:</span>
                <span className="info-value">
                  {qualityStatistics.token_statistics.avg_tokens_per_page.toLocaleString()}
                </span>
              </div>
            </div>
          </div>
        )}
        <div className="card">
          <div className="card-header">
            <h3 className="card-title">Информация о маппинге</h3>
          </div>
          <div className="info-grid">
            <div className="info-item">
              <span className="info-label">Brizy Project ID:</span>
              <span className="info-value">{details.mapping.brz_project_id}</span>
            </div>
            <div className="info-item">
              <span className="info-label">MB Project UUID:</span>
              <span className="info-value uuid">{formatUUID(details.mapping.mb_project_uuid)}</span>
            </div>
            <div className="info-item">
              <span className="info-label">Создано:</span>
              <span className="info-value">{formatDate(details.mapping.created_at)}</span>
            </div>
            <div className="info-item">
              <span className="info-label">Обновлено:</span>
              <span className="info-value">{formatDate(details.mapping.updated_at)}</span>
            </div>
          </div>
          {details.mapping.changes_json && (
            <div className="json-section">
              <h4>Changes JSON:</h4>
              <div className="json-viewer">
                <pre>
                  {typeof changesJson === 'object' && changesJson !== null
                    ? JSON.stringify(changesJson, null, 2)
                    : typeof details.mapping.changes_json === 'string'
                    ? details.mapping.changes_json.substring(0, 500) + (details.mapping.changes_json.length > 500 ? '... (truncated)' : '')
                    : 'Invalid JSON data'}
                </pre>
              </div>
            </div>
          )}
        </div>

        {(details.result || migrationValue || changesJson) && (
          <div className="card">
            <div className="card-header">
              <h3 className="card-title">Результат миграции</h3>
            </div>
            <div className="info-grid">
              {details.result?.migration_uuid && (
                <div className="info-item">
                  <span className="info-label">Migration UUID:</span>
                  <span className="info-value uuid">{formatUUID(details.result.migration_uuid)}</span>
                </div>
              )}
              {(migrationValue?.brizy_project_domain || (details as any).brizy_project_domain || changesJson?.brizy_project_domain) && (
                <div className="info-item">
                  <span className="info-label">Brizy Project Domain:</span>
                  <span className="info-value">
                    <a 
                      href={migrationValue?.brizy_project_domain || (details as any).brizy_project_domain || changesJson?.brizy_project_domain} 
                      target="_blank" 
                      rel="noopener noreferrer"
                    >
                      {migrationValue?.brizy_project_domain || (details as any).brizy_project_domain || changesJson?.brizy_project_domain}
                    </a>
                  </span>
                </div>
              )}
              {migrationValue?.mb_project_domain && (
                <div className="info-item">
                  <span className="info-label">MB Project Domain:</span>
                  <span className="info-value">{migrationValue.mb_project_domain}</span>
                </div>
              )}
              {migrationValue?.migration_id && (
                <div className="info-item">
                  <span className="info-label">Migration ID:</span>
                  <span className="info-value uuid">{migrationValue.migration_id}</span>
                </div>
              )}
              {migrationValue?.date && (
                <div className="info-item">
                  <span className="info-label">Дата миграции:</span>
                  <span className="info-value">{migrationValue.date}</span>
                </div>
              )}
              {migrationValue?.theme && (
                <div className="info-item">
                  <span className="info-label">Тема:</span>
                  <span className="info-value">{migrationValue.theme}</span>
                </div>
              )}
              {migrationValue?.mb_product_name && (
                <div className="info-item">
                  <span className="info-label">MB Product Name:</span>
                  <span className="info-value">{migrationValue.mb_product_name}</span>
                </div>
              )}
              {migrationValue?.mb_site_id && (
                <div className="info-item">
                  <span className="info-label">MB Site ID:</span>
                  <span className="info-value">{migrationValue.mb_site_id}</span>
                </div>
              )}
              {migrationValue?.progress && (
                <div className="info-item">
                  <span className="info-label">Прогресс:</span>
                  <span className="info-value">
                    {migrationValue.progress.Success || 0} / {migrationValue.progress.Total || 0}
                    {migrationValue.progress.processTime && (
                      <span style={{ fontSize: '0.875rem', color: '#666', marginLeft: '0.5rem' }}>
                        ({migrationValue.progress.processTime.toFixed(1)}s)
                      </span>
                    )}
                  </span>
                </div>
              )}
              {migrationValue?.DEV_MODE !== undefined && (
                <div className="info-item">
                  <span className="info-label">DEV Mode:</span>
                  <span className="info-value">{migrationValue.DEV_MODE ? 'Да' : 'Нет'}</span>
                </div>
              )}
              {changesJson?.completed_at && (
                <div className="info-item">
                  <span className="info-label">Завершено:</span>
                  <span className="info-value">{formatDate(changesJson.completed_at)}</span>
                </div>
              )}
            </div>
            {migrationValue?.message?.warning && migrationValue.message.warning.length > 0 && (
              <div className="json-section">
                <h4>Предупреждения:</h4>
                <div className="warnings-list">
                  {migrationValue.message.warning.map((warning: string, index: number) => (
                    <div key={index} className="warning-item">
                      {warning}
                    </div>
                  ))}
                </div>
              </div>
            )}
            {resultData && (
              <div className="json-section">
                <h4>Полный JSON ответа:</h4>
                <div className="json-viewer">
                  <pre>{JSON.stringify(resultData, null, 2)}</pre>
                </div>
              </div>
            )}
          </div>
        )}

        <div className="card">
          <div className="card-header">
            <h3 className="card-title">Действия</h3>
          </div>
          <div className="actions">
            <button
              onClick={() => setShowRestartForm(!showRestartForm)}
              className="btn btn-primary"
              disabled={details.status === 'in_progress'}
            >
              {showRestartForm ? 'Отменить' : 'Перезапустить миграцию'}
            </button>
            {details.status === 'in_progress' && (
              <button onClick={loadDetails} className="btn btn-secondary">
                Обновить статус
              </button>
            )}
          </div>

          {showRestartForm && (
            <div className="restart-form">
              <h4>Параметры перезапуска</h4>
              <div className="form-group">
                <label className="form-label">
                  MB Site ID
                  {defaultSettings.mb_site_id && (
                    <span className="form-default-badge">(из настроек: {defaultSettings.mb_site_id})</span>
                  )}
                </label>
                <input
                  type="number"
                  className="form-input"
                  value={restartParams.mb_site_id}
                  onChange={(e) => setRestartParams({ ...restartParams, mb_site_id: e.target.value })}
                  placeholder={defaultSettings.mb_site_id ? String(defaultSettings.mb_site_id) : "31383"}
                />
                <div className="form-help">
                  ID сайта в Ministry Brands
                  {!defaultSettings.mb_site_id && (
                    <span className="form-help-hint"> (можно задать в <a href="/dashboard/settings">настройках</a>)</span>
                  )}
                </div>
              </div>
              <div className="form-group">
                <label className="form-label">
                  MB Secret
                  {defaultSettings.mb_secret && (
                    <span className="form-default-badge">(из настроек: ••••••••)</span>
                  )}
                </label>
                <input
                  type="password"
                  className="form-input"
                  value={restartParams.mb_secret}
                  onChange={(e) => setRestartParams({ ...restartParams, mb_secret: e.target.value })}
                  placeholder={defaultSettings.mb_secret ? "••••••••" : "b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q"}
                />
                <div className="form-help">
                  Секретный ключ для доступа к MB API
                  {!defaultSettings.mb_secret && (
                    <span className="form-help-hint"> (можно задать в <a href="/dashboard/settings">настройках</a>)</span>
                  )}
                </div>
              </div>
              <div className="form-group">
                <label className="form-label">Brizy Workspaces ID</label>
                <input
                  type="number"
                  className="form-input"
                  value={restartParams.brz_workspaces_id}
                  onChange={(e) => setRestartParams({ ...restartParams, brz_workspaces_id: e.target.value })}
                  placeholder="22925473"
                />
              </div>
              <div className="form-group">
                <label className="form-label">MB Page Slug</label>
                <input
                  type="text"
                  className="form-input"
                  value={restartParams.mb_page_slug}
                  onChange={(e) => setRestartParams({ ...restartParams, mb_page_slug: e.target.value })}
                  placeholder="home"
                />
              </div>
              <div className="form-group">
                <label className="form-label">Manual</label>
                <select
                  className="form-input"
                  value={restartParams.mgr_manual}
                  onChange={(e) => setRestartParams({ ...restartParams, mgr_manual: e.target.value })}
                >
                  <option value="0">Автоматически</option>
                  <option value="1">Вручную</option>
                </select>
              </div>

              <div className="form-group">
                <label className="form-label checkbox-label">
                  <input
                    type="checkbox"
                    checked={restartParams.quality_analysis || false}
                    onChange={(e) => setRestartParams({ ...restartParams, quality_analysis: e.target.checked })}
                    className="form-checkbox"
                  />
                  <span>Включить анализ качества миграции</span>
                </label>
                <div className="form-help">
                  При включении старые результаты анализа будут помечены как устаревшие, и будет выполнен новый анализ
                </div>
              </div>

              <button
                onClick={handleRestart}
                className="btn btn-success"
                disabled={restarting}
              >
                {restarting ? 'Перезапуск...' : 'Перезапустить'}
              </button>
            </div>
          )}
        </div>
      </div>
      )}

      {activeTab === 'analysis' && (
        <QualityAnalysis />
      )}

      {activeTab === 'archive' && (
        <QualityAnalysisArchive migrationId={parseInt(id || '0')} />
      )}
    </div>
  );
}

// Компонент для отображения архивных результатов анализа
function QualityAnalysisArchive({ migrationId }: { migrationId: number }) {
  const [reports, setReports] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedPage, setSelectedPage] = useState<string | null>(null);

  useEffect(() => {
    loadArchivedReports();
  }, [migrationId]);

  const loadArchivedReports = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await api.getArchivedQualityAnalysis(migrationId);
      if (response.success && response.data && Array.isArray(response.data)) {
        setReports(response.data);
      } else {
        setReports([]);
        if (response.error && !response.error.includes('не найден')) {
          setError(response.error);
        }
      }
    } catch (err: any) {
      console.error('Error loading archived reports:', err);
      setError(err.message || 'Ошибка загрузки архивных отчетов');
      setReports([]);
    } finally {
      setLoading(false);
    }
  };

  const getQualityScoreColor = (score?: number) => {
    if (!score) return '#6c757d';
    if (score >= 90) return '#198754';
    if (score >= 70) return '#ffc107';
    if (score >= 50) return '#fd7e14';
    return '#dc3545';
  };

  const formatCost = (cost?: number) => {
    if (cost === undefined || cost === null) return 'N/A';
    return `$${cost.toFixed(6)}`;
  };

  const formatTokens = (tokens?: number) => {
    if (tokens === undefined || tokens === null) return 'N/A';
    return tokens.toLocaleString();
  };

  if (loading) {
    return (
      <div className="loading-container">
        <div className="spinner"></div>
        <p>Загрузка архивных результатов...</p>
      </div>
    );
  }

  if (error && reports.length === 0) {
    return (
      <div className="error-container">
        <p className="error-message">❌ {error}</p>
        <button onClick={loadArchivedReports} className="btn btn-primary">
          Попробовать снова
        </button>
      </div>
    );
  }

  if (reports.length === 0) {
    return (
      <div className="quality-analysis-empty">
        <p>Архивных результатов анализа нет.</p>
        <p className="text-muted">Архивные результаты появляются после перезапуска миграции с анализом качества.</p>
      </div>
    );
  }

  return (
    <div className="quality-analysis">
      <div className="archive-header">
        <h3>📦 Архивные результаты анализа</h3>
        <p className="text-muted">Эти результаты были помечены как устаревшие после перезапуска миграции с анализом качества.</p>
      </div>

      <div className="quality-pages-list">
        <div className="pages-grid">
          {reports.map((report) => (
            <div
              key={report.id}
              className={`page-card archived-page ${selectedPage === report.page_slug ? 'selected' : ''}`}
              onClick={() => setSelectedPage(report.page_slug)}
            >
              <div className="page-card-header">
                <h4>{report.page_slug || 'Без названия'}</h4>
                <span className="archived-badge">Архив</span>
              </div>
              <div className="page-card-body">
                {report.quality_score !== null && report.quality_score !== undefined && (
                  <div className="quality-score">
                    <span className="score-label">Рейтинг:</span>
                    <span
                      className="score-value"
                      style={{ color: getQualityScoreColor(typeof report.quality_score === 'string' ? parseInt(report.quality_score) : report.quality_score) }}
                    >
                      {typeof report.quality_score === 'string' ? parseInt(report.quality_score) : report.quality_score}
                    </span>
                  </div>
                )}
                {report.token_usage && (
                  <div className="page-tokens-info">
                    <div className="tokens-row">
                      <span className="tokens-label">Токены:</span>
                      <span className="tokens-value">
                        {formatTokens(report.token_usage.total_tokens)}
                      </span>
                    </div>
                    {report.token_usage.cost_estimate_usd !== undefined && report.token_usage.cost_estimate_usd !== null && (
                      <div className="tokens-row">
                        <span className="tokens-label">Стоимость:</span>
                        <span className="tokens-value cost-value" style={{ color: '#198754', fontWeight: 'bold' }}>
                          {formatCost(report.token_usage.cost_estimate_usd)}
                        </span>
                      </div>
                    )}
                  </div>
                )}
                <div className="page-meta">
                  <span className="meta-item">
                    {new Date(report.created_at).toLocaleDateString()}
                  </span>
                  <span className="meta-item archived-status">📦 Архивирован</span>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>

      {selectedPage && (
        <ArchivedPageAnalysisDetails
          migrationId={migrationId}
          pageSlug={selectedPage}
          onClose={() => setSelectedPage(null)}
        />
      )}
    </div>
  );
}

// Компонент для отображения деталей архивной страницы
function ArchivedPageAnalysisDetails({ migrationId, pageSlug, onClose }: { migrationId: number; pageSlug: string; onClose: () => void }) {
  const [report, setReport] = useState<any | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [activeTab, setActiveTab] = useState<'overview' | 'screenshots' | 'issues'>('overview');

  useEffect(() => {
    loadPageAnalysis();
  }, [migrationId, pageSlug]);

  const loadPageAnalysis = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await api.getPageQualityAnalysis(migrationId, pageSlug, true); // includeArchived = true
      if (response.success && response.data) {
        setReport(response.data);
      } else {
        setError(response.error || 'Не удалось загрузить детали анализа');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка загрузки деталей');
    } finally {
      setLoading(false);
    }
  };

  const getSeverityColor = (severity: string) => {
    switch (severity) {
      case 'critical': return '#dc3545';
      case 'high': return '#fd7e14';
      case 'medium': return '#ffc107';
      case 'low': return '#0dcaf0';
      case 'none': return '#198754';
      default: return '#6c757d';
    }
  };

  const getQualityScoreColor = (score?: number) => {
    if (!score) return '#6c757d';
    if (score >= 90) return '#198754';
    if (score >= 70) return '#ffc107';
    if (score >= 50) return '#fd7e14';
    return '#dc3545';
  };

  if (loading) {
    return (
      <div className="page-analysis-modal">
        <div className="modal-content">
          <div className="loading-container">
            <div className="spinner"></div>
            <p>Загрузка деталей анализа...</p>
          </div>
        </div>
      </div>
    );
  }

  if (error || !report) {
    return (
      <div className="page-analysis-modal">
        <div className="modal-content">
          <div className="error-container">
            <p className="error-message">❌ {error || 'Анализ не найден'}</p>
            <button onClick={onClose} className="btn btn-secondary">
              Закрыть
            </button>
          </div>
        </div>
      </div>
    );
  }

  const sourceScreenshot = report.screenshots_path?.source;
  const migratedScreenshot = report.screenshots_path?.migrated;
  const sourceFilename = sourceScreenshot ? sourceScreenshot.split('/').pop() : null;
  const migratedFilename = migratedScreenshot ? migratedScreenshot.split('/').pop() : null;

  return (
    <div className="page-analysis-modal" onClick={onClose}>
      <div className="modal-content" onClick={(e) => e.stopPropagation()}>
        <div className="modal-header">
          <h2>📦 Архив: Анализ страницы: {report.page_slug}</h2>
          <button onClick={onClose} className="btn-close">×</button>
        </div>

        <div className="modal-tabs">
          <button
            className={activeTab === 'overview' ? 'active' : ''}
            onClick={() => setActiveTab('overview')}
          >
            Обзор
          </button>
          <button
            className={activeTab === 'screenshots' ? 'active' : ''}
            onClick={() => setActiveTab('screenshots')}
          >
            Скриншоты
          </button>
          <button
            className={activeTab === 'issues' ? 'active' : ''}
            onClick={() => setActiveTab('issues')}
          >
            Проблемы
          </button>
        </div>

        <div className="modal-body">
          {activeTab === 'overview' && (
            <div className="overview-tab">
              <div className="info-grid">
                <div className="info-item highlight-item">
                  <span className="info-label">📦 Статус:</span>
                  <span className="info-value" style={{ color: '#6c757d' }}>Архивирован</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Рейтинг качества:</span>
                  <span
                    className="info-value"
                    style={{ color: getQualityScoreColor(typeof report.quality_score === 'string' ? parseInt(report.quality_score) : report.quality_score) }}
                  >
                    {report.quality_score !== null && report.quality_score !== undefined 
                      ? (typeof report.quality_score === 'string' ? parseInt(report.quality_score) : report.quality_score)
                      : 'N/A'}
                  </span>
                </div>
                <div className="info-item">
                  <span className="info-label">Уровень критичности:</span>
                  <span
                    className="info-value"
                    style={{ color: getSeverityColor(report.severity_level) }}
                  >
                    {report.severity_level}
                  </span>
                </div>
                {report.token_usage && (
                  <>
                    <div className="info-item highlight-item">
                      <span className="info-label">💰 Стоимость анализа:</span>
                      <span className="info-value" style={{ color: '#198754', fontWeight: 'bold', fontSize: '1.2em' }}>
                        ${report.token_usage.cost_estimate_usd !== undefined && report.token_usage.cost_estimate_usd !== null
                          ? report.token_usage.cost_estimate_usd.toFixed(6)
                          : 'N/A'}
                      </span>
                    </div>
                    <div className="info-item">
                      <span className="info-label">Всего токенов:</span>
                      <span className="info-value">
                        {report.token_usage.total_tokens !== undefined && report.token_usage.total_tokens !== null
                          ? report.token_usage.total_tokens.toLocaleString()
                          : 'N/A'}
                      </span>
                    </div>
                    <div className="info-item">
                      <span className="info-label">Входные токены (prompt):</span>
                      <span className="info-value">
                        {report.token_usage.prompt_tokens !== undefined && report.token_usage.prompt_tokens !== null
                          ? report.token_usage.prompt_tokens.toLocaleString()
                          : 'N/A'}
                      </span>
                    </div>
                    <div className="info-item">
                      <span className="info-label">Выходные токены (completion):</span>
                      <span className="info-value">
                        {report.token_usage.completion_tokens !== undefined && report.token_usage.completion_tokens !== null
                          ? report.token_usage.completion_tokens.toLocaleString()
                          : 'N/A'}
                      </span>
                    </div>
                    {report.token_usage.model && (
                      <div className="info-item">
                        <span className="info-label">Модель AI:</span>
                        <span className="info-value">{report.token_usage.model}</span>
                      </div>
                    )}
                  </>
                )}
                {report.source_url && (
                  <div className="info-item">
                    <span className="info-label">Исходная страница:</span>
                    <span className="info-value">
                      <a href={report.source_url} target="_blank" rel="noopener noreferrer">
                        {report.source_url}
                      </a>
                    </span>
                  </div>
                )}
                {report.migrated_url && (
                  <div className="info-item">
                    <span className="info-label">Мигрированная страница:</span>
                    <span className="info-value">
                      <a href={report.migrated_url} target="_blank" rel="noopener noreferrer">
                        {report.migrated_url}
                      </a>
                    </span>
                  </div>
                )}
                <div className="info-item">
                  <span className="info-label">Дата анализа:</span>
                  <span className="info-value">
                    {new Date(report.created_at).toLocaleString()}
                  </span>
                </div>
              </div>

              {report.issues_summary?.summary && (
                <div className="summary-section">
                  <h3>Краткое описание</h3>
                  <p>{report.issues_summary.summary}</p>
                </div>
              )}
            </div>
          )}

          {activeTab === 'screenshots' && (
            <div className="screenshots-tab">
              <div className="screenshots-grid">
                {sourceScreenshot && sourceFilename && (
                  <div className="screenshot-item">
                    <h4>Исходная страница</h4>
                    <img
                      src={api.getScreenshotUrl(sourceFilename)}
                      alt="Source screenshot"
                      className="screenshot-image"
                      onError={(e) => {
                        (e.target as HTMLImageElement).src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="300"%3E%3Ctext x="50%25" y="50%25" text-anchor="middle" dy=".3em"%3EСкриншот не найден%3C/text%3E%3C/svg%3E';
                      }}
                    />
                    <p className="screenshot-path">{sourceScreenshot}</p>
                  </div>
                )}
                {migratedScreenshot && migratedFilename && (
                  <div className="screenshot-item">
                    <h4>Мигрированная страница</h4>
                    <img
                      src={api.getScreenshotUrl(migratedFilename)}
                      alt="Migrated screenshot"
                      className="screenshot-image"
                      onError={(e) => {
                        (e.target as HTMLImageElement).src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="300"%3E%3Ctext x="50%25" y="50%25" text-anchor="middle" dy=".3em"%3EСкриншот не найден%3C/text%3E%3C/svg%3E';
                      }}
                    />
                    <p className="screenshot-path">{migratedScreenshot}</p>
                  </div>
                )}
                {!sourceScreenshot && !migratedScreenshot && (
                  <div className="no-screenshots">
                    <p>Скриншоты недоступны</p>
                  </div>
                )}
              </div>
            </div>
          )}

          {activeTab === 'issues' && (
            <div className="issues-tab">
              {report.issues_summary?.missing_elements && report.issues_summary.missing_elements.length > 0 && (
                <div className="issues-section">
                  <h3>Отсутствующие элементы</h3>
                  <ul>
                    {report.issues_summary.missing_elements.map((item: string, index: number) => (
                      <li key={index}>{item}</li>
                    ))}
                  </ul>
                </div>
              )}

              {report.issues_summary?.changed_elements && report.issues_summary.changed_elements.length > 0 && (
                <div className="issues-section">
                  <h3>Измененные элементы</h3>
                  <ul>
                    {report.issues_summary.changed_elements.map((item: string, index: number) => (
                      <li key={index}>{item}</li>
                    ))}
                  </ul>
                </div>
              )}

              {report.issues_summary?.recommendations && report.issues_summary.recommendations.length > 0 && (
                <div className="issues-section">
                  <h3>Рекомендации</h3>
                  <ul>
                    {report.issues_summary.recommendations.map((item: string, index: number) => (
                      <li key={index}>{item}</li>
                    ))}
                  </ul>
                </div>
              )}

              {(!report.issues_summary?.missing_elements?.length &&
                !report.issues_summary?.changed_elements?.length &&
                !report.issues_summary?.recommendations?.length) && (
                <div className="no-issues">
                  <p>Проблем не обнаружено</p>
                </div>
              )}

              {report.detailed_report && (
                <div className="issues-section">
                  <h3>Детальный отчет</h3>
                  <div className="json-viewer">
                    <pre>{JSON.stringify(report.detailed_report, null, 2)}</pre>
                  </div>
                </div>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
