#!/bin/bash
git pull origin master
supervisorctl restart hyperf_blog
