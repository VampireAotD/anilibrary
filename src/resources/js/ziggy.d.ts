/* This file is generated by Ziggy. */
declare module 'ziggy-js' {
    interface RouteList {
        'debugbar.openhandler': [];
        'debugbar.clockwork': [
            {
                name: 'id';
                required: true;
            },
        ];
        'debugbar.assets.css': [];
        'debugbar.assets.js': [];
        'debugbar.cache.delete': [
            {
                name: 'key';
                required: true;
            },
            {
                name: 'tags';
                required: false;
            },
        ];
        'debugbar.queries.explain': [];
        'horizon.stats.index': [];
        'horizon.workload.index': [];
        'horizon.masters.index': [];
        'horizon.monitoring.index': [];
        'horizon.monitoring.store': [];
        'horizon.monitoring-tag.paginate': [
            {
                name: 'tag';
                required: true;
            },
        ];
        'horizon.monitoring-tag.destroy': [
            {
                name: 'tag';
                required: true;
            },
        ];
        'horizon.jobs-metrics.index': [];
        'horizon.jobs-metrics.show': [
            {
                name: 'id';
                required: true;
            },
        ];
        'horizon.queues-metrics.index': [];
        'horizon.queues-metrics.show': [
            {
                name: 'id';
                required: true;
            },
        ];
        'horizon.jobs-batches.index': [];
        'horizon.jobs-batches.show': [
            {
                name: 'id';
                required: true;
            },
        ];
        'horizon.jobs-batches.retry': [
            {
                name: 'id';
                required: true;
            },
        ];
        'horizon.pending-jobs.index': [];
        'horizon.completed-jobs.index': [];
        'horizon.silenced-jobs.index': [];
        'horizon.failed-jobs.index': [];
        'horizon.failed-jobs.show': [
            {
                name: 'id';
                required: true;
            },
        ];
        'horizon.retry-jobs.show': [
            {
                name: 'id';
                required: true;
            },
        ];
        'horizon.jobs.show': [
            {
                name: 'id';
                required: true;
            },
        ];
        'horizon.index': [
            {
                name: 'view';
                required: false;
            },
        ];
        'sanctum.csrf-cookie': [];
        'log-viewer.hosts': [];
        'log-viewer.folders': [];
        'log-viewer.folders.request-download': [
            {
                name: 'folderIdentifier';
                required: true;
            },
        ];
        'log-viewer.folders.clear-cache': [
            {
                name: 'folderIdentifier';
                required: true;
            },
        ];
        'log-viewer.folders.delete': [
            {
                name: 'folderIdentifier';
                required: true;
            },
        ];
        'log-viewer.files': [];
        'log-viewer.files.request-download': [
            {
                name: 'fileIdentifier';
                required: true;
            },
        ];
        'log-viewer.files.clear-cache': [
            {
                name: 'fileIdentifier';
                required: true;
            },
        ];
        'log-viewer.files.delete': [
            {
                name: 'fileIdentifier';
                required: true;
            },
        ];
        'log-viewer.files.clear-cache-all': [];
        'log-viewer.files.delete-multiple-files': [];
        'log-viewer.logs': [];
        'log-viewer.folders.download': [
            {
                name: 'folderIdentifier';
                required: true;
            },
        ];
        'log-viewer.files.download': [
            {
                name: 'fileIdentifier';
                required: true;
            },
        ];
        'log-viewer.index': [
            {
                name: 'view';
                required: false;
            },
        ];
        'ignition.healthCheck': [];
        'ignition.executeSolution': [];
        'ignition.updateConfig': [];
        dashboard: [];
        'registration_access.await': [];
        'registration_access.request': [];
        'registration_access.acquire': [];
        register: [];
        login: [];
        'password.request': [];
        'password.email': [];
        'password.reset': [
            {
                name: 'token';
                required: true;
            },
        ];
        'password.store': [];
        'verification.notice': [];
        'verification.verify': [
            {
                name: 'id';
                required: true;
            },
            {
                name: 'hash';
                required: true;
            },
        ];
        'verification.send': [];
        'password.confirm': [];
        'password.update': [];
        logout: [];
        'invitation.resend': [
            {
                name: 'invitation';
                required: true;
                binding: 'id';
            },
        ];
        'invitation.index': [];
        'invitation.send': [];
        'invitation.accept': [
            {
                name: 'invitation';
                required: true;
                binding: 'id';
            },
        ];
        'invitation.decline': [
            {
                name: 'invitation';
                required: true;
                binding: 'id';
            },
        ];
        'anime.random': [];
        'anime.index': [];
        'anime.create': [];
        'anime.store': [];
        'anime.show': [
            {
                name: 'anime';
                required: true;
                binding: 'id';
            },
        ];
        'anime.update': [
            {
                name: 'anime';
                required: true;
                binding: 'id';
            },
        ];
        'anime-list.index': [];
        'anime-list.store': [];
        'anime-list.update': [
            {
                name: 'anime';
                required: true;
                binding: 'id';
            },
        ];
        'anime-list.destroy': [
            {
                name: 'anime';
                required: true;
                binding: 'id';
            },
        ];
        'profile.edit': [];
        'profile.update': [];
        'profile.destroy': [];
        'telegram.assign': [];
    }
}
export {};