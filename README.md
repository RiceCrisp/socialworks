# README #

Walker Sands Base Wordpress Theme (_ws)

### Local Setup ###
1. Install NPM/Node and Docker
2. Run `docker-compose pull` to get the latest docker images.
3. In a terminal in the root of the application, start the docker container `docker-compose up -d`
    - If there are docker containers already running, then run the command `docker stop $(docker ps -aq) && docker container prune -f && docker network prune -f` first to kill all containers. Save this command as an alias to save yourself time in the future.
4. Install dependencies `npm install`
5. Start the development task to start building files and watch for changes (live-reloading) `npm run dev`
    - Site should now be available at *localhost*

#### Troubleshooting ####
- If you get an error that certain files are not known to Docker, confirm that your folder is listed as a directory or subdirectory of the "File Sharing" section of Docker preferences.
- If you get an error that ports are already being used, make sure your native apache and/or mysql is not running by using this command `sudo apachectl stop && sudo service mysqld stop`
- Make sure you don't have any overlapping Docker containers running. This can be checked with `docker ps`
- If you need to access the bash shell of the wordpress docker container, use command `docker exec -ti CONTAINER_NAME bash`
- If you want to change the project folder name, but don't want to lose any work that you've already done (because changing the project folder name will create new docker containers/volumes), use the command `docker-compose -p OLD_FOLDER_NAME up -d` to reference the old containers/volumes.
- To see the php error log, use this command `docker logs -f CONTAINER_NAME >/dev/null`

### WP Engine Setup ###
Since we want to build our files before we push them, Git isn't ideal for handling code deployment. WP Engine, however, offers SSH connections, so we can use SSH and a tool called rsync to handle deploying code.

1. Start a new project with a clone of the Walker Sands Base
    1. `git clone git@bitbucket.org:walkersandsdigital/walkersands_base_wp-theme.git FOLDER_NAME`
    2. `cd FOLDER_NAME`
    3. `rm -r .git`
    4. `git init`
2. Create new Bitbucket repo
    - Owner: Walker Sands Digital
    - Project: Clients
3. Setup Pipelines
    1. Enable Pipelines
    2. Generate SSH key
    3. Fetch and add the WP Engine SSH host name as a known host (ENV_NAME.ssh.wpengine.net)
    4. Add the following relevant secured repository variables: STAG_SSH_USER, STAG_SSH_HOST, PROD_SSH_USER, PROD_SSH_HOST
4. Add newly generated Bitbucket SSH public key to the WP Engine account (not to the "git push" options)
5. Make first commit to master
    1. `git add .`
    2. `git commit -m 'First commit'`
    3. `git remote add origin git@bitbucket.org:walkersandsdigital/REPO_NAME.git`
    4. `git push -u origin master`
6. Run pipeline in Bitbucket to deploy to WP Engine (may have to wait a couple minutes for WP Engine to add SSH key)

### Non WP Engine Setup ###
If the client is on a host other than WP Engine, try to setup the ssh/rsync first, but if not available, we have a SFTP fallback.

1. Copy Walker Sands Base and start new project
    1. `git clone git@bitbucket.org:walkersandsdigital/walkersands_base_wp-theme.git FOLDER_NAME`
    2. `cd FOLDER_NAME`
    3. `rm -r .git`
    4. `git init`
2. Create new Bitbucket repo
    - Owner: Walker Sands Digital
    - Project: Clients
3. Setup Pipelines
    1. Enable Pipelines
    2. Fetch and add the SFTP hostname with port number as a known host (HOSTNAME:PORT)
    3. Add the following relevant secured repository variables: STAG_SFTP_HOST, STAG_SFTP_PORT, STAG_SFTP_PATH, STAG_SFTP_USER, STAG_SFTP_PASSWORD, PROD_SFTP_HOST, PROD_SFTP_PORT, PROD_SFTP_PATH, PROD_SFTP_USER, PROD_SFTP_PASSWORD
    4. Modify `bitbucket-pipelines.yml` to utilize the commented code correctly
4. Make first commit to master
    1. `git add .`
    2. `git commit -m 'First commit'`
    3. `git remote add origin git@bitbucket.org:walkersandsdigital/REPO_NAME.git`
    4. `git push -u origin master`
5. Run pipeline in Bitbucket to deploy to WP Engine (may have to wait a couple minutes for WP Engine to add SSH key)  
