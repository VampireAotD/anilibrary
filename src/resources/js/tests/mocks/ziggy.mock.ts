const Ziggy = {
    url: 'http://anilibrary.test',
    routes: {
        dashboard: { uri: '/', methods: ['GET', 'HEAD'] },
        'profile.edit': { uri: 'profile', methods: ['GET', 'HEAD'] },
        'anime.index': { uri: 'anime', methods: ['GET', 'HEAD'] },
        'invitation.create': { uri: 'invitation', methods: ['GET', 'HEAD'] },
        logout: { uri: 'logout', methods: ['POST'] },
    },
};

export { Ziggy };
