const Ziggy = {
    url: 'http:\/\/localhost',
    port: null,
    defaults: {},
    routes: {
        'debugbar.openhandler': { uri: '_debugbar\/open', methods: ['GET', 'HEAD'] },
        'debugbar.clockwork': {
            uri: '_debugbar\/clockwork\/{id}',
            methods: ['GET', 'HEAD'],
            parameters: ['id'],
        },
        'debugbar.assets.css': {
            uri: '_debugbar\/assets\/stylesheets',
            methods: ['GET', 'HEAD'],
        },
        'debugbar.assets.js': {
            uri: '_debugbar\/assets\/javascript',
            methods: ['GET', 'HEAD'],
        },
        'debugbar.cache.delete': {
            uri: '_debugbar\/cache\/{key}\/{tags?}',
            methods: ['DELETE'],
            parameters: ['key', 'tags'],
        },
        'debugbar.queries.explain': {
            uri: '_debugbar\/queries\/explain',
            methods: ['POST'],
        },
        'horizon.stats.index': { uri: 'horizon\/api\/stats', methods: ['GET', 'HEAD'] },
        'horizon.workload.index': {
            uri: 'horizon\/api\/workload',
            methods: ['GET', 'HEAD'],
        },
        'horizon.masters.index': {
            uri: 'horizon\/api\/masters',
            methods: ['GET', 'HEAD'],
        },
        'horizon.monitoring.index': {
            uri: 'horizon\/api\/monitoring',
            methods: ['GET', 'HEAD'],
        },
        'horizon.monitoring.store': {
            uri: 'horizon\/api\/monitoring',
            methods: ['POST'],
        },
        'horizon.monitoring-tag.paginate': {
            uri: 'horizon\/api\/monitoring\/{tag}',
            methods: ['GET', 'HEAD'],
            parameters: ['tag'],
        },
        'horizon.monitoring-tag.destroy': {
            uri: 'horizon\/api\/monitoring\/{tag}',
            methods: ['DELETE'],
            wheres: { tag: '.*' },
            parameters: ['tag'],
        },
        'horizon.jobs-metrics.index': {
            uri: 'horizon\/api\/metrics\/jobs',
            methods: ['GET', 'HEAD'],
        },
        'horizon.jobs-metrics.show': {
            uri: 'horizon\/api\/metrics\/jobs\/{id}',
            methods: ['GET', 'HEAD'],
            parameters: ['id'],
        },
        'horizon.queues-metrics.index': {
            uri: 'horizon\/api\/metrics\/queues',
            methods: ['GET', 'HEAD'],
        },
        'horizon.queues-metrics.show': {
            uri: 'horizon\/api\/metrics\/queues\/{id}',
            methods: ['GET', 'HEAD'],
            parameters: ['id'],
        },
        'horizon.jobs-batches.index': {
            uri: 'horizon\/api\/batches',
            methods: ['GET', 'HEAD'],
        },
        'horizon.jobs-batches.show': {
            uri: 'horizon\/api\/batches\/{id}',
            methods: ['GET', 'HEAD'],
            parameters: ['id'],
        },
        'horizon.jobs-batches.retry': {
            uri: 'horizon\/api\/batches\/retry\/{id}',
            methods: ['POST'],
            parameters: ['id'],
        },
        'horizon.pending-jobs.index': {
            uri: 'horizon\/api\/jobs\/pending',
            methods: ['GET', 'HEAD'],
        },
        'horizon.completed-jobs.index': {
            uri: 'horizon\/api\/jobs\/completed',
            methods: ['GET', 'HEAD'],
        },
        'horizon.silenced-jobs.index': {
            uri: 'horizon\/api\/jobs\/silenced',
            methods: ['GET', 'HEAD'],
        },
        'horizon.failed-jobs.index': {
            uri: 'horizon\/api\/jobs\/failed',
            methods: ['GET', 'HEAD'],
        },
        'horizon.failed-jobs.show': {
            uri: 'horizon\/api\/jobs\/failed\/{id}',
            methods: ['GET', 'HEAD'],
            parameters: ['id'],
        },
        'horizon.retry-jobs.show': {
            uri: 'horizon\/api\/jobs\/retry\/{id}',
            methods: ['POST'],
            parameters: ['id'],
        },
        'horizon.jobs.show': {
            uri: 'horizon\/api\/jobs\/{id}',
            methods: ['GET', 'HEAD'],
            parameters: ['id'],
        },
        'horizon.index': {
            uri: 'horizon\/{view?}',
            methods: ['GET', 'HEAD'],
            wheres: { view: '(.*)' },
            parameters: ['view'],
        },
        'sanctum.csrf-cookie': { uri: 'sanctum\/csrf-cookie', methods: ['GET', 'HEAD'] },
        'log-viewer.hosts': { uri: 'log-viewer\/api\/hosts', methods: ['GET', 'HEAD'] },
        'log-viewer.folders': {
            uri: 'log-viewer\/api\/folders',
            methods: ['GET', 'HEAD'],
        },
        'log-viewer.folders.request-download': {
            uri: 'log-viewer\/api\/folders\/{folderIdentifier}\/download\/request',
            methods: ['GET', 'HEAD'],
            parameters: ['folderIdentifier'],
        },
        'log-viewer.folders.clear-cache': {
            uri: 'log-viewer\/api\/folders\/{folderIdentifier}\/clear-cache',
            methods: ['POST'],
            parameters: ['folderIdentifier'],
        },
        'log-viewer.folders.delete': {
            uri: 'log-viewer\/api\/folders\/{folderIdentifier}',
            methods: ['DELETE'],
            parameters: ['folderIdentifier'],
        },
        'log-viewer.files': { uri: 'log-viewer\/api\/files', methods: ['GET', 'HEAD'] },
        'log-viewer.files.request-download': {
            uri: 'log-viewer\/api\/files\/{fileIdentifier}\/download\/request',
            methods: ['GET', 'HEAD'],
            parameters: ['fileIdentifier'],
        },
        'log-viewer.files.clear-cache': {
            uri: 'log-viewer\/api\/files\/{fileIdentifier}\/clear-cache',
            methods: ['POST'],
            parameters: ['fileIdentifier'],
        },
        'log-viewer.files.delete': {
            uri: 'log-viewer\/api\/files\/{fileIdentifier}',
            methods: ['DELETE'],
            parameters: ['fileIdentifier'],
        },
        'log-viewer.files.clear-cache-all': {
            uri: 'log-viewer\/api\/clear-cache-all',
            methods: ['POST'],
        },
        'log-viewer.files.delete-multiple-files': {
            uri: 'log-viewer\/api\/delete-multiple-files',
            methods: ['POST'],
        },
        'log-viewer.logs': { uri: 'log-viewer\/api\/logs', methods: ['GET', 'HEAD'] },
        'log-viewer.folders.download': {
            uri: 'log-viewer\/api\/folders\/{folderIdentifier}\/download',
            methods: ['GET', 'HEAD'],
            parameters: ['folderIdentifier'],
        },
        'log-viewer.files.download': {
            uri: 'log-viewer\/api\/files\/{fileIdentifier}\/download',
            methods: ['GET', 'HEAD'],
            parameters: ['fileIdentifier'],
        },
        'log-viewer.index': {
            uri: 'log-viewer\/{view?}',
            methods: ['GET', 'HEAD'],
            wheres: { view: '(.*)' },
            parameters: ['view'],
        },
        'ignition.healthCheck': {
            uri: '_ignition\/health-check',
            methods: ['GET', 'HEAD'],
        },
        'ignition.executeSolution': {
            uri: '_ignition\/execute-solution',
            methods: ['POST'],
        },
        'ignition.updateConfig': { uri: '_ignition\/update-config', methods: ['POST'] },
        dashboard: { uri: '\/', methods: ['GET', 'HEAD'] },
        'registration_access.await': { uri: 'register\/await', methods: ['GET', 'HEAD'] },
        'registration_access.request': {
            uri: 'register\/request',
            methods: ['GET', 'HEAD'],
        },
        'registration_access.acquire': { uri: 'register\/request', methods: ['POST'] },
        register: { uri: 'register', methods: ['GET', 'HEAD'] },
        login: { uri: 'login', methods: ['GET', 'HEAD'] },
        'password.request': { uri: 'forgot-password', methods: ['GET', 'HEAD'] },
        'password.email': { uri: 'forgot-password', methods: ['POST'] },
        'password.reset': {
            uri: 'reset-password\/{token}',
            methods: ['GET', 'HEAD'],
            parameters: ['token'],
        },
        'password.store': { uri: 'reset-password', methods: ['POST'] },
        'verification.notice': { uri: 'verify-email', methods: ['GET', 'HEAD'] },
        'verification.verify': {
            uri: 'verify-email\/{id}\/{hash}',
            methods: ['GET', 'HEAD'],
            parameters: ['id', 'hash'],
        },
        'verification.send': {
            uri: 'email\/verification-notification',
            methods: ['POST'],
        },
        'password.confirm': { uri: 'confirm-password', methods: ['GET', 'HEAD'] },
        'password.update': { uri: 'password', methods: ['PUT'] },
        logout: { uri: 'logout', methods: ['POST'] },
        'invitation.resend': {
            uri: 'invitation\/{invitation}\/resend',
            methods: ['POST'],
            parameters: ['invitation'],
            bindings: { invitation: 'id' },
        },
        'invitation.index': { uri: 'invitation', methods: ['GET', 'HEAD'] },
        'invitation.send': { uri: 'invitation', methods: ['POST'] },
        'invitation.accept': {
            uri: 'invitation\/{invitation}',
            methods: ['PUT', 'PATCH'],
            parameters: ['invitation'],
            bindings: { invitation: 'id' },
        },
        'invitation.decline': {
            uri: 'invitation\/{invitation}',
            methods: ['DELETE'],
            parameters: ['invitation'],
            bindings: { invitation: 'id' },
        },
        'anime.random': { uri: 'anime\/random', methods: ['GET', 'HEAD'] },
        'anime.index': { uri: 'anime', methods: ['GET', 'HEAD'] },
        'anime.create': { uri: 'anime\/create', methods: ['GET', 'HEAD'] },
        'anime.store': { uri: 'anime', methods: ['POST'] },
        'anime.show': {
            uri: 'anime\/{anime}',
            methods: ['GET', 'HEAD'],
            parameters: ['anime'],
            bindings: { anime: 'id' },
        },
        'anime.update': {
            uri: 'anime\/{anime}',
            methods: ['PUT', 'PATCH'],
            parameters: ['anime'],
            bindings: { anime: 'id' },
        },
        'anime-list.index': { uri: 'anime-list', methods: ['GET', 'HEAD'] },
        'anime-list.store': { uri: 'anime-list', methods: ['POST'] },
        'anime-list.update': {
            uri: 'anime-list\/{anime}',
            methods: ['PUT', 'PATCH'],
            parameters: ['anime'],
            bindings: { anime: 'id' },
        },
        'anime-list.destroy': {
            uri: 'anime-list\/{anime}',
            methods: ['DELETE'],
            parameters: ['anime'],
            bindings: { anime: 'id' },
        },
        'profile.edit': { uri: 'profile', methods: ['GET', 'HEAD'] },
        'profile.update': { uri: 'profile', methods: ['PATCH'] },
        'profile.destroy': { uri: 'profile', methods: ['DELETE'] },
        'telegram.assign': { uri: 'telegram\/assign', methods: ['POST'] },
    },
};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
