pipeline {
    agent any
    environment {
        PROJECT_ID = 'trial-velostrata'
        CLUSTER_NAME = 'test'
        LOCATION = 'asia-southeast1'
        CREDENTIALS_ID = 'gke'
    }
    stages {
        stage("Checkout code") {
            steps {
                checkout scm
            }
        }
        stage("Build image") {
            steps {
                script {
                    myapp = docker.build("anggit/cuaca:${env.BUILD_ID}")
                }
            }
        }
        stage("Push image") {
            steps {
                script {
                    docker.withRegistry('https://registry.hub.docker.com', 'dockerhub') {
                            myapp.push("latest")
                            myapp.push("${env.BUILD_ID}")
                    }
                }
            }
        }        
        stage('Deploy to GKE') {
            steps{
                sh "kubectl config use-context gke_trial-velostrata_asia-southeast1_test"
                sh "sed -i 's/hello:latest/cuaca:${env.BUILD_ID}/g' deployment.yaml"
                sh "kubectl apply -f /var/lib/jenkins/workspace/cuaca/deployment.yaml"
            }
        }
    }    
}
