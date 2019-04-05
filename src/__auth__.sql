--
-- Table structure for table `__auth__credential_email`
--

CREATE TABLE IF NOT EXISTS `__auth__credential_email` (
  `id` int(11) NOT NULL,
  `profileId` bigint(20) NOT NULL,
  `passwordHash` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `isEmailVerified` tinyint(1) NOT NULL DEFAULT '0',
  `updatedAt` int(11) NOT NULL,
  `createdAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `__auth__credential_fb`
--

CREATE TABLE IF NOT EXISTS `__auth__credential_fb` (
  `id` int(11) NOT NULL,
  `profileId` bigint(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `data` text,
  `updatedAt` int(11) NOT NULL,
  `createdAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `__auth__profile`
--

CREATE TABLE IF NOT EXISTS `__auth__profile` (
  `id` bigint(20) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `updatedAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `__auth__session`
--

CREATE TABLE IF NOT EXISTS `__auth__session` (
  `id` bigint(20) NOT NULL,
  `authToken` varchar(36) NOT NULL,
  `refreshToken` varchar(36) NOT NULL,
  `profileId` bigint(20) NOT NULL,
  `type` enum('fb','email') NOT NULL,
  `expiresIn` int(11) NOT NULL,
  `deleteIn` int(11) DEFAULT NULL,
  `pushToken` varchar(256) DEFAULT NULL,
  `platform` enum('ios','ios_dev','android','web') NOT NULL,
  `version` varchar(10) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `updatedAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `__auth__credential_email`
--
ALTER TABLE `__auth__credential_email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `profileId` (`profileId`);

--
-- Indexes for table `__auth__credential_fb`
--
ALTER TABLE `__auth__credential_fb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `profileId` (`profileId`);

--
-- Indexes for table `__auth__profile`
--
ALTER TABLE `__auth__profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `__auth__session`
--
ALTER TABLE `__auth__session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `access_token` (`authToken`),
  ADD UNIQUE KEY `refresh_token` (`refreshToken`),
  ADD KEY `user_id` (`profileId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `__auth__credential_email`
--
ALTER TABLE `__auth__credential_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `__auth__credential_fb`
--
ALTER TABLE `__auth__credential_fb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `__auth__profile`
--
ALTER TABLE `__auth__profile`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `__auth__session`
--
ALTER TABLE `__auth__session`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `__auth__credential_email`
--
ALTER TABLE `__auth__credential_email`
  ADD CONSTRAINT `__auth__credential_email_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `__auth__profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `__auth__credential_fb`
--
ALTER TABLE `__auth__credential_fb`
  ADD CONSTRAINT `__auth__credential_fb_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `__auth__profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `__auth__session`
--
ALTER TABLE `__auth__session`
  ADD CONSTRAINT `__auth__session_ibfk_1` FOREIGN KEY (`profileId`) REFERENCES `__auth__profile` (`id`) ON DELETE CASCADE;
