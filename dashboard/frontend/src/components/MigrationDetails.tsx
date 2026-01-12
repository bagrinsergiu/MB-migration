import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { api, MigrationDetails as MigrationDetailsType } from '../api/client';
import { getStatusConfig } from '../utils/status';
import { formatDate, formatUUID } from '../utils/format';
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
  });
  const [defaultSettings, setDefaultSettings] = useState<{ mb_site_id?: number; mb_secret?: string }>({});

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
      // Обновляем статус каждые 5 секунд если миграция в процессе
      const interval = setInterval(() => {
        if (details?.status === 'in_progress') {
          loadDetails();
        }
      }, 5000);
      return () => clearInterval(interval);
    }
  }, [id, details?.status]);

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
  const resultData = details.result?.result_json
    ? (typeof details.result.result_json === 'string'
        ? JSON.parse(details.result.result_json)
        : details.result.result_json)
    : null;
  
  // Извлекаем данные из value, если они там находятся, или используем result_data из API
  const migrationValue = (details as any).result_data || resultData?.value || resultData;
  
  // Также проверяем changes_json для получения данных
  const changesJson = details.mapping.changes_json 
    ? (typeof details.mapping.changes_json === 'string'
        ? JSON.parse(details.mapping.changes_json)
        : details.mapping.changes_json)
    : null;
  
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

      <div className="details-grid">
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
                <pre>{JSON.stringify(details.mapping.changes_json, null, 2)}</pre>
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
    </div>
  );
}
