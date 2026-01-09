import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Layout from './components/Layout';
import MigrationsList from './components/MigrationsList';
import MigrationDetails from './components/MigrationDetails';
import RunMigration from './components/RunMigration';
import Logs from './components/Logs';
import { Settings } from './components/Settings';
import Wave from './components/Wave';
import WaveDetails from './components/WaveDetails';
import WaveMapping from './components/WaveMapping';
import './App.css';

function App() {
  return (
    <BrowserRouter basename="/dashboard">
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
        </Routes>
      </Layout>
    </BrowserRouter>
  );
}

export default App;
