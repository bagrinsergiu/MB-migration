import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { api, Migration } from '../api/client';
import { getStatusConfig } from '../utils/status';
import { formatDate, formatUUID } from '../utils/format';
import './MigrationsList.css';
import './common.css';

export default function MigrationsList() {
  const [migrations, setMigrations] = useState<Migration[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [filters, setFilters] = useState({
    status: '',
    mb_project_uuid: '',
    brz_project_id: '',
  });

  useEffect(() => {
    loadMigrations();
  }, [filters]);

  const loadMigrations = async () => {
    try {
      setLoading(true);
      setError(null);
      const params: any = {};
      if (filters.status) params.status = filters.status;
      if (filters.mb_project_uuid) params.mb_project_uuid = filters.mb_project_uuid;
      if (filters.brz_project_id) params.brz_project_id = parseInt(filters.brz_project_id);

      const response = await api.getMigrations(params);
      if (response.success && response.data) {
        setMigrations(response.data);
      } else {
        setError(response.error || 'Ошибка загрузки миграций');
      }
    } catch (err: any) {
      setError(err.message || 'Ошибка загрузки миграций');
    } finally {
      setLoading(false);
    }
  };

  const handleFilterChange = (key: string, value: string) => {
    setFilters(prev => ({ ...prev, [key]: value }));
  };

  const clearFilters = () => {
    setFilters({
      status: '',
      mb_project_uuid: '',
      brz_project_id: '',
    });
  };

  if (loading) {
    return (
      <div className="loading-container">
        <div className="spinner"></div>
        <p>Загрузка миграций...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="error-container">
        <p className="error-message">❌ {error}</p>
        <button onClick={loadMigrations} className="btn btn-primary">
          Попробовать снова
        </button>
      </div>
    );
  }

  return (
    <div className="migrations-list">
      <div className="page-header">
        <h2>Список миграций</h2>
        <Link to="/run" className="btn btn-primary">
          + Запустить миграцию
        </Link>
      </div>

      <div className="filters">
        <div className="filter-group">
          <label>Статус:</label>
          <select
            value={filters.status}
            onChange={(e) => handleFilterChange('status', e.target.value)}
          >
            <option value="">Все</option>
            <option value="pending">Ожидает</option>
            <option value="in_progress">Выполняется</option>
            <option value="success">Успешно</option>
            <option value="error">Ошибка</option>
          </select>
        </div>

        <div className="filter-group">
          <label>MB UUID:</label>
          <input
            type="text"
            placeholder="Поиск по UUID..."
            value={filters.mb_project_uuid}
            onChange={(e) => handleFilterChange('mb_project_uuid', e.target.value)}
          />
        </div>

        <div className="filter-group">
          <label>Brizy ID:</label>
          <input
            type="number"
            placeholder="ID проекта..."
            value={filters.brz_project_id}
            onChange={(e) => handleFilterChange('brz_project_id', e.target.value)}
          />
        </div>

        <button onClick={clearFilters} className="btn btn-secondary">
          Сбросить
        </button>
      </div>

      <div className="stats">
        <div className="stat-card">
          <div className="stat-value">{migrations.length}</div>
          <div className="stat-label">Всего миграций</div>
        </div>
        <div className="stat-card">
          <div className="stat-value success">
            {migrations.filter(m => m.status === 'success').length}
          </div>
          <div className="stat-label">Успешных</div>
        </div>
        <div className="stat-card">
          <div className="stat-value error">
            {migrations.filter(m => m.status === 'error').length}
          </div>
          <div className="stat-label">С ошибками</div>
        </div>
        <div className="stat-card">
          <div className="stat-value info">
            {migrations.filter(m => m.status === 'in_progress').length}
          </div>
          <div className="stat-label">В процессе</div>
        </div>
      </div>

      <div className="table-container">
        <table className="migrations-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>MB UUID</th>
              <th>Brizy ID</th>
              <th>Статус</th>
              <th>Создано</th>
              <th>Обновлено</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            {migrations.length === 0 ? (
              <tr>
                <td colSpan={7} className="empty-state">
                  Миграции не найдены
                </td>
              </tr>
            ) : (
              migrations.map((migration) => {
                const statusConfig = getStatusConfig(migration.status);
                return (
                  <tr key={migration.id}>
                    <td>{migration.brz_project_id}</td>
                    <td className="uuid-cell">{formatUUID(migration.mb_project_uuid)}</td>
                    <td>{migration.brz_project_id}</td>
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
                    <td>{formatDate(migration.created_at)}</td>
                    <td>{formatDate(migration.updated_at)}</td>
                    <td>
                      <Link
                        to={`/migrations/${migration.brz_project_id}`}
                        className="btn btn-sm btn-link"
                      >
                        Детали
                      </Link>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
