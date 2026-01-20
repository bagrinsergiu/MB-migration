import { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { api, QualityAnalysisReport, QualityStatistics } from '../api/client';
import './QualityAnalysis.css';
import './common.css';

export default function QualityAnalysis() {
  const { id } = useParams<{ id: string }>();
  const [reports, setReports] = useState<QualityAnalysisReport[]>([]);
  const [statistics, setStatistics] = useState<QualityStatistics | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedPage, setSelectedPage] = useState<string | null>(null);

  useEffect(() => {
    if (id) {
      loadAnalysis();
    }
  }, [id]);

  const loadAnalysis = async () => {
    if (!id) return;
    try {
      setLoading(true);
      setError(null);
      
      const [reportsResponse, statsResponse] = await Promise.allSettled([
        api.getQualityAnalysis(parseInt(id)),
        api.getQualityStatistics(parseInt(id))
      ]);

      // Обработка отчетов
      if (reportsResponse.status === 'fulfilled') {
        const response = reportsResponse.value;
        if (response.success && response.data && Array.isArray(response.data)) {
          setReports(response.data);
        } else {
          // Если нет данных анализа - это не ошибка, просто пустой список
          if (response.error && !response.error.includes('не найден') && !response.error.includes('Request failed')) {
            setError(response.error);
          } else {
            setReports([]);
          }
        }
      } else {
        // Ошибка при запросе отчетов
        console.error('Error loading reports:', reportsResponse.reason);
        setReports([]);
        // Не показываем ошибку если это просто отсутствие данных
        if (reportsResponse.reason?.response?.status !== 404) {
          setError('Ошибка загрузки отчетов анализа');
        }
      }

      // Обработка статистики
      if (statsResponse.status === 'fulfilled') {
        const response = statsResponse.value;
        if (response.success && response.data) {
          setStatistics(response.data);
        } else {
          // Статистика опциональна, не показываем ошибку если её нет
          setStatistics(null);
        }
      } else {
        // Ошибка при запросе статистики - игнорируем, статистика опциональна
        console.error('Error loading statistics:', statsResponse.reason);
        setStatistics(null);
      }
    } catch (err: any) {
      console.error('Error loading quality analysis:', err);
      setError(err.message || 'Ошибка загрузки анализа');
      setReports([]);
      setStatistics(null);
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

  const formatTokens = (tokens?: number) => {
    if (tokens === undefined || tokens === null) return 'N/A';
    return tokens.toLocaleString();
  };

  const formatCost = (cost?: number) => {
    if (cost === undefined || cost === null) return 'N/A';
    return `$${cost.toFixed(6)}`;
  };

  if (loading) {
    return (
      <div className="loading-container">
        <div className="spinner"></div>
        <p>Загрузка анализа качества...</p>
      </div>
    );
  }

  if (error && reports.length === 0) {
    return (
      <div className="error-container">
        <p className="error-message">❌ {error}</p>
        <button onClick={loadAnalysis} className="btn btn-primary">
          Попробовать снова
        </button>
      </div>
    );
  }

  if (reports.length === 0) {
    return (
      <div className="quality-analysis-empty">
        <p>Анализ качества для этой миграции еще не выполнен.</p>
        <p className="text-muted">Запустите миграцию с параметром <code>quality_analysis=true</code> для выполнения анализа.</p>
      </div>
    );
  }

  return (
    <div className="quality-analysis">
      {statistics && (
        <div className="quality-statistics">
          <div className="stat-card">
            <div className="stat-label">Всего страниц</div>
            <div className="stat-value">{statistics.total_pages}</div>
          </div>
          <div className="stat-card">
            <div className="stat-label">Средний рейтинг</div>
            <div className="stat-value" style={{ color: getQualityScoreColor(statistics.avg_quality_score) }}>
              {typeof statistics.avg_quality_score === 'number' ? statistics.avg_quality_score.toFixed(1) : 'N/A'}
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-label">Критичные</div>
            <div className="stat-value" style={{ color: getSeverityColor('critical') }}>
              {statistics.by_severity.critical}
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-label">Высокие</div>
            <div className="stat-value" style={{ color: getSeverityColor('high') }}>
              {statistics.by_severity.high}
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-label">Средние</div>
            <div className="stat-value" style={{ color: getSeverityColor('medium') }}>
              {statistics.by_severity.medium}
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-label">Низкие</div>
            <div className="stat-value" style={{ color: getSeverityColor('low') }}>
              {statistics.by_severity.low}
            </div>
          </div>
        </div>
      )}

      <div className="quality-pages-list">
        <h3>Анализ страниц</h3>
        <div className="pages-grid">
          {reports.map((report) => (
            <div
              key={report.id}
              className={`page-card ${selectedPage === report.page_slug ? 'selected' : ''}`}
              onClick={() => setSelectedPage(report.page_slug)}
            >
              <div className="page-card-header">
                <h4>{report.page_slug || 'Без названия'}</h4>
                <span
                  className="severity-badge"
                  style={{
                    backgroundColor: getSeverityColor(report.severity_level),
                    color: 'white'
                  }}
                >
                  {report.severity_level}
                </span>
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
                        {report.token_usage.prompt_tokens && report.token_usage.completion_tokens && (
                          <span className="tokens-detail">
                            {' '}({formatTokens(report.token_usage.prompt_tokens)}/{formatTokens(report.token_usage.completion_tokens)})
                          </span>
                        )}
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
                {report.issues_summary?.summary && (
                  <div className="page-summary">
                    {report.issues_summary.summary.substring(0, 100)}
                    {report.issues_summary.summary.length > 100 ? '...' : ''}
                  </div>
                )}
                <div className="page-meta">
                  <span className="meta-item">
                    {new Date(report.created_at).toLocaleDateString()}
                  </span>
                  {report.analysis_status === 'completed' && (
                    <span className="meta-item status-completed">✓ Завершен</span>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>

      {selectedPage && (
        <PageAnalysisDetails
          migrationId={parseInt(id || '0')}
          pageSlug={selectedPage}
          onClose={() => setSelectedPage(null)}
        />
      )}
    </div>
  );
}

export interface PageAnalysisDetailsProps {
  migrationId: number;
  pageSlug: string;
  onClose: () => void;
}

export function PageAnalysisDetails({ migrationId, pageSlug, onClose }: PageAnalysisDetailsProps) {
  const [report, setReport] = useState<QualityAnalysisReport | null>(null);
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
      const response = await api.getPageQualityAnalysis(migrationId, pageSlug);
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
          <h2>Анализ страницы: {report.page_slug}</h2>
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
                <div className="info-item">
                  <span className="info-label">Статус анализа:</span>
                  <span className="info-value">{report.analysis_status}</span>
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
                    {report.issues_summary.missing_elements.map((item, index) => (
                      <li key={index}>{item}</li>
                    ))}
                  </ul>
                </div>
              )}

              {report.issues_summary?.changed_elements && report.issues_summary.changed_elements.length > 0 && (
                <div className="issues-section">
                  <h3>Измененные элементы</h3>
                  <ul>
                    {report.issues_summary.changed_elements.map((item, index) => (
                      <li key={index}>{item}</li>
                    ))}
                  </ul>
                </div>
              )}

              {report.issues_summary?.recommendations && report.issues_summary.recommendations.length > 0 && (
                <div className="issues-section">
                  <h3>Рекомендации</h3>
                  <ul>
                    {report.issues_summary.recommendations.map((item, index) => (
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
