import { Link, useLocation } from 'react-router-dom';
import { useTheme } from '../contexts/ThemeContext';
import './Layout.css';

interface LayoutProps {
  children: React.ReactNode;
}

export default function Layout({ children }: LayoutProps) {
  const location = useLocation();
  const { theme, toggleTheme } = useTheme();

  const isActive = (path: string) => location.pathname === path;

  return (
    <div className="layout">
      <header className="header">
        <div className="header-content">
          <h1 className="logo">
            <Link to="/">🚀 MB Migration Dashboard</Link>
          </h1>
          <div className="header-right">
            <nav className="nav">
              <Link to="/" className={isActive('/') ? 'active' : ''}>
                Миграции
              </Link>
              <Link to="/run" className={isActive('/run') ? 'active' : ''}>
                Запустить
              </Link>
              <Link to="/wave" className={isActive('/wave') || location.pathname.startsWith('/wave/') ? 'active' : ''}>
                Волны
              </Link>
              <Link to="/test" className={isActive('/test') || location.pathname.startsWith('/test/') ? 'active' : ''}>
                Тестирование
              </Link>
              <Link to="/logs" className={isActive('/logs') ? 'active' : ''}>
                Логи
              </Link>
              <Link to="/settings" className={isActive('/settings') ? 'active' : ''}>
                Настройки
              </Link>
            </nav>
            <button className="theme-toggle" onClick={toggleTheme} aria-label="Переключить тему">
              {theme === 'light' ? '🌙' : '☀️'}
            </button>
          </div>
        </div>
      </header>
      <main className="main">
        {children}
      </main>
    </div>
  );
}
