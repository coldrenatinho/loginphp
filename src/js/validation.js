/**
 * =====================================================
 * Sistema de Login - JavaScript para Validações
 * Desenvolvido para complementar o Bootstrap 5
 * =====================================================
 */

// =====================================================
// Validação de Formulário de Cadastro
// =====================================================
function validateCadastroForm() {
    const form = document.getElementById('cadastroForm');
    
    if (!form) return;

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();

        // Remove classes de validação anteriores
        form.classList.remove('was-validated');

        let isValid = true;

        // Validação do Nome Completo
        const nome = document.getElementById('nome');
        if (nome && nome.value.trim().length < 3) {
            setInvalid(nome, 'O nome deve ter pelo menos 3 caracteres.');
            isValid = false;
        } else if (nome) {
            setValid(nome);
        }

        // Validação do Sobrenome
        const sobrenome = document.getElementById('sobrenome');
        if (sobrenome && sobrenome.value.trim().length < 2) {
            setInvalid(sobrenome, 'O sobrenome deve ter pelo menos 2 caracteres.');
            isValid = false;
        } else if (sobrenome) {
            setValid(sobrenome);
        }

        // Validação de Email
        const email = document.getElementById('email');
        if (email && !validateEmail(email.value)) {
            setInvalid(email, 'Por favor, insira um email válido.');
            isValid = false;
        } else if (email) {
            setValid(email);
        }

        // Validação de Senha
        const senha = document.getElementById('senha');
        const senhaStrength = checkPasswordStrength(senha.value);
        
        if (senha && senha.value.length < 6) {
            setInvalid(senha, 'A senha deve ter pelo menos 6 caracteres.');
            isValid = false;
        } else if (senha && senhaStrength === 'weak') {
            setInvalid(senha, 'A senha é muito fraca. Use letras maiúsculas, minúsculas e números.');
            isValid = false;
        } else if (senha) {
            setValid(senha);
        }

        // Validação de Confirmação de Senha
        const confirmarSenha = document.getElementById('confirmar_senha');
        if (confirmarSenha && confirmarSenha.value !== senha.value) {
            setInvalid(confirmarSenha, 'As senhas não coincidem.');
            isValid = false;
        } else if (confirmarSenha) {
            setValid(confirmarSenha);
        }

        // Se tudo estiver válido, envia o formulário
        if (isValid) {
            form.classList.add('was-validated');
            form.submit();
        }
    });
}

// =====================================================
// Validação de Formulário de Login
// =====================================================
function validateLoginForm() {
    const form = document.getElementById('loginForm');
    
    if (!form) return;

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();

        let isValid = true;

        // Validação de Email
        const email = document.getElementById('email');
        if (email && !validateEmail(email.value)) {
            setInvalid(email, 'Por favor, insira um email válido.');
            isValid = false;
        } else if (email) {
            setValid(email);
        }

        // Validação de Senha
        const senha = document.getElementById('senha');
        if (senha && senha.value.length === 0) {
            setInvalid(senha, 'Por favor, insira sua senha.');
            isValid = false;
        } else if (senha) {
            setValid(senha);
        }

        // Se tudo estiver válido, envia o formulário
        if (isValid) {
            form.classList.add('was-validated');
            form.submit();
        }
    });
}

// =====================================================
// Validação de Formulário de Recuperação de Senha
// =====================================================
function validateRecuperarSenhaForm() {
    const form = document.getElementById('recuperarSenhaForm');
    
    if (!form) return;

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();

        let isValid = true;

        // Validação de Email
        const email = document.getElementById('email');
        if (email && !validateEmail(email.value)) {
            setInvalid(email, 'Por favor, insira um email válido.');
            isValid = false;
        } else if (email) {
            setValid(email);
        }

        // Se tudo estiver válido, envia o formulário
        if (isValid) {
            form.classList.add('was-validated');
            form.submit();
        }
    });
}

// =====================================================
// Função para Validar Email
// =====================================================
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// =====================================================
// Função para Verificar Força da Senha
// =====================================================
function checkPasswordStrength(password) {
    let strength = 'weak';
    
    if (password.length >= 8) {
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        
        const criteriaCount = [hasLower, hasUpper, hasNumber, hasSpecial].filter(Boolean).length;
        
        if (criteriaCount >= 3) {
            strength = 'strong';
        } else if (criteriaCount >= 2) {
            strength = 'medium';
        }
    } else if (password.length >= 6) {
        strength = 'medium';
    }
    
    return strength;
}

// =====================================================
// Indicador de Força da Senha em Tempo Real
// =====================================================
function setupPasswordStrengthIndicator() {
    const senhaInput = document.getElementById('senha');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    
    if (!senhaInput || !strengthBar || !strengthText) return;

    senhaInput.addEventListener('input', function() {
        const password = senhaInput.value;
        const strength = checkPasswordStrength(password);
        
        // Remove classes anteriores
        strengthBar.classList.remove('weak', 'medium', 'strong');
        
        if (password.length > 0) {
            strengthBar.classList.add(strength);
            strengthBar.style.display = 'block';
            
            switch(strength) {
                case 'weak':
                    strengthText.textContent = 'Senha Fraca';
                    strengthText.style.color = '#e74a3b';
                    break;
                case 'medium':
                    strengthText.textContent = 'Senha Média';
                    strengthText.style.color = '#f6c23e';
                    break;
                case 'strong':
                    strengthText.textContent = 'Senha Forte';
                    strengthText.style.color = '#1cc88a';
                    break;
            }
        } else {
            strengthBar.style.display = 'none';
            strengthText.textContent = '';
        }
    });
}

// =====================================================
// Funções Auxiliares para Validação
// =====================================================
function setInvalid(element, message) {
    element.classList.remove('is-valid');
    element.classList.add('is-invalid');
    
    const feedback = element.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = message;
    } else {
        const newFeedback = document.createElement('div');
        newFeedback.className = 'invalid-feedback';
        newFeedback.textContent = message;
        element.parentNode.insertBefore(newFeedback, element.nextSibling);
    }
}

function setValid(element) {
    element.classList.remove('is-invalid');
    element.classList.add('is-valid');
    
    const feedback = element.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = '';
    }
}

// =====================================================
// Auto-hide de Alertas
// =====================================================
function setupAutoHideAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
}

// =====================================================
// Mostrar/Ocultar Senha
// =====================================================
function setupPasswordToggle() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

// =====================================================
// Confirmação de Logout
// =====================================================
function confirmLogout(event) {
    if (!confirm('Tem certeza que deseja sair?')) {
        event.preventDefault();
        return false;
    }
    return true;
}

// =====================================================
// Inicialização ao Carregar a Página
// =====================================================
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa validações de formulários
    validateCadastroForm();
    validateLoginForm();
    validateRecuperarSenhaForm();
    
    // Inicializa indicador de força da senha
    setupPasswordStrengthIndicator();
    
    // Inicializa auto-hide de alertas
    setupAutoHideAlerts();
    
    // Inicializa toggle de senha
    setupPasswordToggle();
    
    // Adiciona animação de fade-in aos cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.classList.add('fade-in');
    });
});
