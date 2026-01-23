import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { ThemeProvider } from './contexts/ThemeContext';
import Layout from './components/Layout';
import MigrationsList from './components/MigrationsList';
import MigrationDetails from './components/MigrationDetails';
import RunMigration from './components/RunMigration';
import Logs from './components/Logs';
import { Settings } from './components/Settings';
import Wave from './components/Wave';
import WaveDetails from './components/WaveDetails';
import WaveMapping from './components/WaveMapping';
import TestMigrationsList from './components/TestMigrationsList';
import TestRunMigration from './components/TestRunMigration';
import TestMigrationDetails from './components/TestMigrationDetails';
import './App.css';

function App() {
  return (
    <ThemeProvider>
      <BrowserRouter 
        basename="/dashboard"
        future={{
          v7_startTransition: true,
          v7_relativeSplatPath: true,
        }}
      >
        <Layout>
          <Routes>
            <Route path="/" element={<MigrationsList />} />
            <Route path="/migrations/:id" element={<MigrationDetails />} />
            <Route path="/run" element={<RunMigration />} />
            <Route path="/logs" element={<Logs />} />
            <Route path="/settings" element={<Settings />} />
                <Route path="/wave" element={<Wave />} />
                <Route path="/wave/:id" element={<WaveDetails />} />
                <Route path="/wave/:id/mapping" element={<WaveMapping />} />
                <Route path="/test" element={<TestMigrationsList />} />
                <Route path="/test/run" element={<TestRunMigration />} />
                <Route path="/test/:id" element={<TestMigrationDetails />} />
          </Routes>
        </Layout>
      </BrowserRouter>
    </ThemeProvider>
  );
}

export default App;
