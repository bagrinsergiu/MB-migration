import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { api, Wave as WaveType } from '../api/client';
import { getStatusConfig } from '../utils/status';
import { formatDate } from '../utils/format';
import './common.css';
import './Wave.css';

export default function Wave() {
  const [waves, setWaves] = useState<WaveType[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [showCreateForm, setShowCreateForm] = useState(false);
  const [creating, setCreating] = useState(false);
  const [createError, setCreateError] = useState<string | null>(null);
  const [formData, setFormData] = useState({
    name: '',
    project_uuids: '',
    batch_size: 3,
    mgr_manual: false,
  });
  const [statusFilter, setStatusFilter] = useState('');

  useEffect(() => {
    loadWaves();
    // Обновляем список каждые 5 секунд если есть активные волны
    const interval = setInterval(() => {
      const hasActiveWaves = waves.some(w => w.status === 'in_progress' || w.status === 'pending');
      if (hasActiveWaves) {
        loadWaves();
      }
    }, 5000);
    return () => clearInterval(interval);
  }, [waves]);

  const loadWaves = async () => {
    try {
      setLoading(true);
      setError(null);
      const filters = statusFilter ? { status: statusFilter } : undefined;
      const response = await api.getWaves(filters);
      if (response.success && response.data) {
        setWaves(response.data);
      } else {
        setError(response.error || 'Ошибка загрузки волн');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка загрузки волн');
    } finally {
      setLoading(false);
    }
  };

  const handleCreateWave = async (e: React.FormEvent) => {
    e.preventDefault();
    setCreating(true);
    setCreateError(null);

    try {
      // Парсим UUID из textarea (по одному на строку)
      const projectUuids = formData.project_uuids
        .split('\n')
        .map(line => line.trim())
        .filter(line => line.length > 0);

      if (projectUuids.length === 0) {
        setCreateError('Введите хотя бы один UUID проекта');
        setCreating(false);
        return;
      }

      const response = await api.createWave({
        name: formData.name,
        project_uuids: projectUuids,
        batch_size: formData.batch_size,
        mgr_manual: formData.mgr_manual,
      });

      if (response.success) {
        setShowCreateForm(false);
        setFormData({
          name: '',
          project_uuids: '',
          batch_size: 3,
          mgr_manual: false,
        });
        loadWaves();
      } else {
        setCreateError(response.error || 'Ошибка создания волны');
      }
    } catch (err: any) {
      setCreateError(err.message || 'Ошибка создания волны');
    } finally {
      setCreating(false);
    }
  };


  if (loading && waves.length === 0) {
    return (
      <div className="loading-container">
        <div className="spinner"></div>
        <p>Загрузка волн миграций...</p>
      </div>
    );
  }

  return (
    <div className="wave-page">
      <div className="page-header">
        <h2>Волны миграций</h2>
        <button
          onClick={() => setShowCreateForm(!showCreateForm)}
          className="btn btn-primary"
        >
          {showCreateForm ? 'Отменить' : '+ Создать волну'}
        </button>
      </div>

      {error && (
        <div className="alert alert-error">
          ❌ {error}
        </div>
      )}

      {showCreateForm && (
        <div className="card">
          <h3>Создать новую волну</h3>
          {createError && (
            <div className="alert alert-error">
              ❌ {createError}
            </div>
          )}
          <form onSubmit={handleCreateWave} className="wave-form">
            <div className="form-group">
              <label className="form-label">
                Название волны <span className="required">*</span>
              </label>
              <input
                type="text"
                className="form-input"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                placeholder="Волна миграций #1"
                required
              />
              <div className="form-help">Название будет использовано для создания workspace в Brizy</div>
            </div>

            <div className="form-group">
              <label className="form-label">
                UUID проектов <span className="required">*</span>
              </label>
              <textarea
                className="form-textarea"
                value={formData.project_uuids}
                onChange={(e) => setFormData({ ...formData, project_uuids: e.target.value })}
                placeholder="3c56530e-ca31-4a7c-964f-e69be01f382a&#10;0c56530e-ca31-4a7c-964f-e69be01f3820&#10;..."
                rows={10}
                required
              />
              <div className="form-help">По одному UUID на строку</div>
            </div>

            <div className="form-group">
              <label className="form-label">Batch Size</label>
              <input
                type="number"
                className="form-input"
                value={formData.batch_size}
                onChange={(e) => setFormData({ ...formData, batch_size: parseInt(e.target.value) || 3 })}
                min="1"
                max="10"
              />
              <div className="form-help">Количество параллельных миграций (по умолчанию: 3)</div>
            </div>

            <div className="form-group">
              <label className="form-label">
                <input
                  type="checkbox"
                  checked={formData.mgr_manual}
                  onChange={(e) => setFormData({ ...formData, mgr_manual: e.target.checked })}
                />
                <span style={{ marginLeft: '0.5rem' }}>Manual Migration</span>
              </label>
              <div className="form-help">Отметить миграции как ручные</div>
            </div>

            <div className="form-actions">
              <button type="submit" className="btn btn-primary" disabled={creating}>
                {creating ? 'Создание...' : 'Создать волну'}
              </button>
              <button
                type="button"
                className="btn btn-secondary"
                onClick={() => {
                  setShowCreateForm(false);
                  setCreateError(null);
                }}
              >
                Отменить
              </button>
            </div>
          </form>
        </div>
      )}

      <div className="filters">
        <div className="filter-group">
          <label>Фильтр по статусу:</label>
          <select
            className="form-select"
            value={statusFilter}
            onChange={(e) => {
              setStatusFilter(e.target.value);
              loadWaves();
            }}
          >
            <option value="">Все</option>
            <option value="pending">Ожидает</option>
            <option value="in_progress">Выполняется</option>
            <option value="completed">Завершено</option>
            <option value="error">Ошибка</option>
          </select>
        </div>
      </div>

      {waves.length === 0 ? (
        <div className="card">
          <p className="empty-message">Волны миграций не найдены</p>
        </div>
      ) : (
        <div className="card">
          <table className="waves-table">
            <thead>
              <tr>
                <th>Название</th>
                <th>Workspace</th>
                <th>Статус</th>
                <th>Прогресс</th>
                <th>Дата создания</th>
                <th>Действия</th>
              </tr>
            </thead>
            <tbody>
              {waves.map((wave) => {
                const statusConfig = getStatusConfig(wave.status);
                const progressPercent = wave.progress.total > 0
                  ? Math.round((wave.progress.completed / wave.progress.total) * 100)
                  : 0;

                return (
                  <tr key={wave.id}>
                    <td>
                      <strong>{wave.name}</strong>
                    </td>
                    <td>{wave.workspace_name}</td>
                    <td>
                      <span
                        className="status-badge"
                        style={{
                          color: statusConfig.color,
                          backgroundColor: statusConfig.bgColor,
                        }}
                      >
                        {statusConfig.label}
                      </span>
                    </td>
                    <td>
                      <div className="progress-info">
                        <span>
                          {wave.progress.completed} / {wave.progress.total}
                          {wave.progress.failed > 0 && (
                            <span style={{ color: '#ef4444', marginLeft: '0.5rem' }}>
                              ({wave.progress.failed} ошибок)
                            </span>
                          )}
                        </span>
                        <div className="progress-bar">
                          <div
                            className="progress-fill"
                            style={{
                              width: `${progressPercent}%`,
                              backgroundColor: wave.progress.failed > 0 ? '#ef4444' : '#10b981',
                            }}
                          />
                        </div>
                      </div>
                    </td>
                    <td>{formatDate(wave.created_at)}</td>
                    <td>
                      <Link
                        to={`/wave/${wave.id}`}
                        className="btn btn-sm btn-primary"
                      >
                        Детали
                      </Link>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
