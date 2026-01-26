import axios from 'axios';

const API_BASE_URL = '/dashboard/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor для обработки ошибок
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    // Пробрасываем ошибку дальше, но с правильной структурой
    if (error.response) {
      // Сервер вернул ошибку с данными
      return Promise.reject(error);
    } else if (error.request) {
      // Запрос был отправлен, но ответа не получено
      return Promise.reject(new Error('Не удалось подключиться к серверу'));
    } else {
      // Ошибка при настройке запроса
      return Promise.reject(error);
    }
  }
);

export interface Migration {
  id: number;
  mb_project_uuid: string;
  brz_project_id: number;
  created_at: string;
  updated_at: string;
  status: 'pending' | 'in_progress' | 'success' | 'completed' | 'error';
  changes_json?: any;
  result?: any;
}

export interface MigrationDetails {
  mapping: {
    brz_project_id: number;
    mb_project_uuid: string;
    changes_json?: any;
    created_at: string;
    updated_at: string;
  };
  result?: {
    migration_uuid: string;
    brz_project_id: number;
    brizy_project_domain?: string;
    mb_project_uuid: string;
    result_json?: any;
  };
  result_data?: any;
  status: 'pending' | 'in_progress' | 'success' | 'error' | 'completed';
  brizy_project_domain?: string;
  mb_project_domain?: string;
  progress?: any;
  warnings?: string[];
}

export interface RunMigrationParams {
  mb_project_uuid: string;
  brz_project_id: number;
  mb_site_id?: number;
  mb_secret?: string;
  brz_workspaces_id?: number;
  mb_page_slug?: string;
  mgr_manual?: number;
  quality_analysis?: boolean;
}

export interface ApiResponse<T> {
  success: boolean;
  data?: T;
  error?: string;
  count?: number;
  details?: any;
}

export const api = {
  // Health check
  async health() {
    const response = await apiClient.get('/health');
    return response.data;
  },

  // Migrations
  async getMigrations(filters?: {
    status?: string;
    mb_project_uuid?: string;
    brz_project_id?: number;
  }): Promise<ApiResponse<Migration[]>> {
    const response = await apiClient.get('/migrations', { params: filters });
    return response.data;
  },

  async getMigrationDetails(id: number): Promise<ApiResponse<MigrationDetails>> {
    const response = await apiClient.get(`/migrations/${id}`);
    return response.data;
  },

  async getMigrationLogs(id: number): Promise<ApiResponse<any>> {
    const response = await apiClient.get(`/migrations/${id}/logs`);
    return response.data;
  },

  async getMigrationStatus(id: number): Promise<ApiResponse<MigrationDetails>> {
    const response = await apiClient.get(`/migrations/${id}/status`);
    return response.data;
  },

  async runMigration(params: RunMigrationParams): Promise<ApiResponse<any>> {
    try {
      const response = await apiClient.post('/migrations/run', params);
      return response.data;
    } catch (error: any) {
      // Обработка ошибок axios
      if (error.response) {
        // Сервер вернул ошибку - возвращаем данные из ответа
        const errorData = error.response.data;
        return {
          success: false,
          error: errorData?.error || errorData?.message || error.response.statusText || 'Ошибка сервера',
          data: errorData?.data
        };
      } else if (error.request) {
        // Запрос был отправлен, но ответа не получено
        return {
          success: false,
          error: 'Не удалось подключиться к серверу. Проверьте подключение к интернету.'
        };
      } else {
        // Ошибка при настройке запроса
        return {
          success: false,
          error: error.message || 'Ошибка при отправке запроса'
        };
      }
    }
  },

  async restartMigration(id: number, params: Partial<RunMigrationParams>): Promise<ApiResponse<any>> {
    const response = await apiClient.post(`/migrations/${id}/restart`, params);
    return response.data;
  },

  async removeMigrationLock(id: number): Promise<ApiResponse<any>> {
    const response = await apiClient.delete(`/migrations/${id}/lock`);
    return response.data;
  },

  async killMigrationProcess(id: number, force: boolean = false): Promise<ApiResponse<any>> {
    const response = await apiClient.post(`/migrations/${id}/kill`, { force });
    return response.data;
  },

  async getMigrationProcessInfo(id: number): Promise<ApiResponse<any>> {
    const response = await apiClient.get(`/migrations/${id}/process`);
    return response.data;
  },

  async removeMigrationCache(id: number): Promise<ApiResponse<any>> {
    const response = await apiClient.delete(`/migrations/${id}/cache`);
    return response.data;
  },

  async resetMigrationStatus(id: number): Promise<ApiResponse<any>> {
    const response = await apiClient.post(`/migrations/${id}/reset-status`);
    return response.data;
  },

  async hardResetMigration(id: number): Promise<ApiResponse<any>> {
    const response = await apiClient.post(`/migrations/${id}/hard-reset`);
    return response.data;
  },

  // Logs
  async getLogs(brzProjectId: number): Promise<ApiResponse<any>> {
    const response = await apiClient.get(`/logs/${brzProjectId}`);
    return response.data;
  },

  async getRecentLogs(limit: number = 10): Promise<ApiResponse<any[]>> {
    const response = await apiClient.get('/logs/recent', { params: { limit } });
    return response.data;
  },

  // Settings
  async getSettings(): Promise<ApiResponse<any>> {
    const response = await apiClient.get('/settings');
    return response.data;
  },

      async saveSettings(settings: { mb_site_id?: number | null; mb_secret?: string | null }): Promise<ApiResponse<any>> {
        const response = await apiClient.post('/settings', settings);
        return response.data;
      },

      // Waves
      async getWaves(filters?: { status?: string }): Promise<ApiResponse<Wave[]>> {
        const response = await apiClient.get('/waves', { params: filters });
        return response.data;
      },

      async getWaveDetails(id: string): Promise<ApiResponse<WaveDetails>> {
        const response = await apiClient.get(`/waves/${id}`);
        return response.data;
      },

      async getWaveStatus(id: string): Promise<ApiResponse<WaveStatus>> {
        const response = await apiClient.get(`/waves/${id}/status`);
        return response.data;
      },

      async getWaveMapping(id: string): Promise<ApiResponse<WaveMapping[]>> {
        const response = await apiClient.get(`/waves/${id}/mapping`);
        return response.data;
      },

      async createWave(params: CreateWaveParams): Promise<ApiResponse<CreateWaveResponse>> {
        const response = await apiClient.post('/waves', params);
        return response.data;
      },

      async restartWaveMigration(waveId: string, mbUuid: string, params?: { mb_site_id?: number; mb_secret?: string }): Promise<ApiResponse<any>> {
        const response = await apiClient.post(`/waves/${waveId}/migrations/${mbUuid}/restart`, params || {});
        return response.data;
      },

      async restartAllWaveMigrations(waveId: string, mbUuids?: string[]): Promise<ApiResponse<any>> {
        const response = await apiClient.post(`/waves/${waveId}/restart-all`, {
          mb_uuids: mbUuids || []
        });
        return response.data;
      },

      async getWaveLogs(waveId: string): Promise<ApiResponse<any>> {
        const response = await apiClient.get(`/waves/${waveId}/logs`);
        return response.data;
      },

      async getWaveMigrationLogs(waveId: string, mbUuid: string): Promise<ApiResponse<any>> {
        const response = await apiClient.get(`/waves/${waveId}/migrations/${mbUuid}/logs`);
        return response.data;
      },

      async removeWaveMigrationLock(waveId: string, mbUuid: string): Promise<ApiResponse<any>> {
        const response = await apiClient.delete(`/waves/${waveId}/migrations/${mbUuid}/lock`);
        return response.data;
      },

      async toggleCloning(waveId: string, brzProjectId: number, cloningEnabled: boolean): Promise<ApiResponse<any>> {
        const response = await apiClient.put(`/waves/${waveId}/mapping/${brzProjectId}/cloning`, {
          cloning_enabled: cloningEnabled
        });
        return response.data;
      },

      // Quality Analysis
      async getQualityAnalysis(migrationId: number): Promise<ApiResponse<QualityAnalysisReport[]>> {
        const response = await apiClient.get(`/migrations/${migrationId}/quality-analysis`);
        return response.data;
      },

      async getArchivedQualityAnalysis(migrationId: number): Promise<ApiResponse<QualityAnalysisReport[]>> {
        const response = await apiClient.get(`/migrations/${migrationId}/quality-analysis/archived`);
        return response.data;
      },

      async getQualityStatistics(migrationId: number): Promise<ApiResponse<QualityStatistics>> {
        const response = await apiClient.get(`/migrations/${migrationId}/quality-analysis/statistics`);
        return response.data;
      },

      async getMigrationPages(migrationId: number): Promise<ApiResponse<any[]>> {
        const response = await apiClient.get(`/migrations/${migrationId}/pages`);
        return response.data;
      },

      async getPageQualityAnalysis(migrationId: number, pageSlug: string, includeArchived: boolean = false): Promise<ApiResponse<QualityAnalysisReport>> {
        const params = includeArchived ? { include_archived: 'true' } : {};
        const response = await apiClient.get(`/migrations/${migrationId}/quality-analysis/${encodeURIComponent(pageSlug)}`, { params });
        return response.data;
      },

      getScreenshotUrl(filename: string): string {
        return `${API_BASE_URL}/screenshots/${filename}`;
      },

      async rebuildPage(migrationId: number, pageSlug: string): Promise<ApiResponse<any>> {
        const response = await apiClient.post(`/migrations/${migrationId}/rebuild-page`, {
          page_slug: pageSlug
        });
        return response.data;
      },

      async rebuildPageNoAnalysis(migrationId: number, pageSlug: string): Promise<ApiResponse<any>> {
        const response = await apiClient.post(`/migrations/${migrationId}/rebuild-page-no-analysis`, {
          page_slug: pageSlug
        });
        return response.data;
      },

      async reanalyzePage(migrationId: number, pageSlug: string): Promise<ApiResponse<any>> {
        try {
          const response = await apiClient.post(`/migrations/${migrationId}/quality-analysis/${encodeURIComponent(pageSlug)}/reanalyze`);
          return response.data;
        } catch (error: any) {
          // Если сервер вернул ошибку с данными, возвращаем их
          if (error.response && error.response.data) {
            return error.response.data;
          }
          // Иначе пробрасываем ошибку дальше
          throw error;
        }
      },

      // Test Migrations
      async getTestMigrations(filters?: {
        status?: string;
        mb_project_uuid?: string;
        brz_project_id?: number;
      }): Promise<ApiResponse<TestMigration[]>> {
        const response = await apiClient.get('/test-migrations', { params: filters });
        return response.data;
      },

      async getTestMigrationDetails(id: number): Promise<ApiResponse<TestMigration>> {
        const response = await apiClient.get(`/test-migrations/${id}`);
        return response.data;
      },

      async createTestMigration(params: CreateTestMigrationParams): Promise<ApiResponse<TestMigration>> {
        const response = await apiClient.post('/test-migrations', params);
        return response.data;
      },

      async updateTestMigration(id: number, params: Partial<CreateTestMigrationParams>): Promise<ApiResponse<TestMigration>> {
        const response = await apiClient.put(`/test-migrations/${id}`, params);
        return response.data;
      },

      async deleteTestMigration(id: number): Promise<ApiResponse<any>> {
        const response = await apiClient.delete(`/test-migrations/${id}`);
        return response.data;
      },

      async runTestMigration(id: number): Promise<ApiResponse<any>> {
        const response = await apiClient.post(`/test-migrations/${id}/run`);
        return response.data;
      },

      async resetTestMigrationStatus(id: number): Promise<ApiResponse<any>> {
        const response = await apiClient.post(`/test-migrations/${id}/reset-status`);
        return response.data;
      },
    };

    export interface Wave {
      id: string;
      name: string;
      workspace_id?: number;
      workspace_name: string;
      status: 'pending' | 'in_progress' | 'completed' | 'error';
      created_at: string;
      updated_at: string;
      completed_at?: string;
      progress: {
        total: number;
        completed: number;
        failed: number;
      };
    }

    export interface WaveDetails {
      wave: Wave;
      migrations: WaveMigration[];
    }

    export interface WaveStatus {
      status: string;
      progress: {
        total: number;
        completed: number;
        failed: number;
      };
    }

    export interface WaveMigration {
      cloning_enabled?: boolean;
      mb_project_uuid: string;
      brz_project_id?: number;
      status: 'pending' | 'in_progress' | 'completed' | 'error';
      brizy_project_domain?: string;
      error?: string;
      completed_at?: string;
      migration_uuid?: string;
      migration_id?: string | number;
      result_data?: {
        migration_id?: string | number;
        date?: string;
        theme?: string;
        mb_product_name?: string;
        mb_site_id?: number;
        progress?: {
          Total?: number;
          Success?: number;
          processTime?: number;
        };
        DEV_MODE?: boolean;
        mb_project_domain?: string;
        warnings?: string[];
      };
    }

    export interface WaveMapping {
      id?: number | null;
      brz_project_id: number;
      mb_project_uuid: string;
      brizy_project_domain?: string | null;
      changes_json?: any;
      cloning_enabled?: boolean;
      created_at: string;
      updated_at: string;
    }

    export interface CreateWaveParams {
      name: string;
      project_uuids: string[];
      batch_size?: number;
      mgr_manual?: boolean;
    }

    export interface CreateWaveResponse {
      wave_id: string;
      workspace_id: number;
      workspace_name: string;
      status: string;
    }

    export interface QualityAnalysisReport {
      id: number;
      migration_id: number;
      mb_project_uuid: string;
      page_slug: string;
      source_url?: string;
      migrated_url?: string;
      analysis_status: 'pending' | 'analyzing' | 'completed' | 'error';
      quality_score?: number | string; // Может быть строкой из API
      severity_level: 'critical' | 'high' | 'medium' | 'low' | 'none';
      token_usage?: {
        prompt_tokens?: number;
        completion_tokens?: number;
        total_tokens?: number;
        estimated_prompt_tokens?: number;
        estimation_accuracy_percent?: number;
        cost_estimate_usd?: number;
        model?: string;
      };
      issues_summary?: {
        summary?: string;
        missing_elements?: string[];
        changed_elements?: string[];
        recommendations?: string[];
      };
      detailed_report?: any;
      screenshots_path?: {
        source?: string;
        migrated?: string;
      };
      created_at: string;
      updated_at: string;
    }

    export interface QualityStatistics {
      total_pages: number;
      avg_quality_score: number | null;
      by_severity: {
        critical: number;
        high: number;
        medium: number;
        low: number;
        none: number;
      };
      token_statistics?: {
        total_prompt_tokens: number;
        total_completion_tokens: number;
        total_tokens: number;
        avg_tokens_per_page: number;
        total_cost_usd: number;
        avg_cost_per_page_usd: number;
      };
    }

    export interface TestMigration {
      id: number;
      mb_project_uuid: string;
      brz_project_id: number;
      mb_site_id?: number;
      mb_secret?: string;
      brz_workspaces_id?: number;
      mb_page_slug?: string;
      mb_element_name?: string;
      skip_media_upload: boolean;
      skip_cache: boolean;
      mgr_manual: number;
      quality_analysis: boolean;
      status: 'pending' | 'in_progress' | 'success' | 'completed' | 'error';
      changes_json?: any;
      section_json?: string | null;
      element_result_json?: string | null;
      created_at: string;
      updated_at: string;
      result?: any;
      migration_uuid?: string;
      brizy_project_domain?: string;
      mb_project_domain?: string;
    }

    export interface CreateTestMigrationParams {
      mb_project_uuid: string;
      brz_project_id: number;
      mb_site_id?: number;
      mb_secret?: string;
      brz_workspaces_id?: number;
      mb_page_slug?: string;
      mb_element_name?: string;
      skip_media_upload?: boolean;
      skip_cache?: boolean;
      mgr_manual?: number;
      quality_analysis?: boolean;
    }

    export default api;
