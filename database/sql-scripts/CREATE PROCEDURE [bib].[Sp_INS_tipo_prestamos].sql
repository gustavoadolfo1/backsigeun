-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [bib].[Sp_INS_tipo_prestamos]
    @_cDescriTipoPrestamo VARCHAR(15),
    /*Campos de auditoria*/
    @_cUsuarioSis VARCHAR(50),
    @_cEquipoSis VARCHAR(50),
    @_cIpSis VARCHAR(15),
    @_cMacNicSis VARCHAR(35)

AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @cUsuarioSis VARCHAR(50)
    SELECT @cUsuarioSis=c.cCredUsuario
    FROM seg.credenciales AS c
    WHERE c.iCredId=@_cUsuarioSis
    IF @@ERROR<>0 GOTO ErrorCapturado

    DECLARE @cMensaje VARCHAR(MAX)

    IF @_cDescriTipoPrestamo IS NULL
		BEGIN
        SET @cMensaje='¡Falta especificar la descripcion del prestamo!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END

    INSERT INTO bib.tipo_prestamos
        (
        cDescriTipoPrestamo,
        cUsuarioSis,
        dFechaSis,
        cEquipoSis,
        cIpSis,
        cOpenUsr,
        cMacNicSis
        )
    VALUES
        (
            @_cDescriTipoPrestamo,
            @_cUsuarioSis,
            GETDATE(),
            @_cEquipoSis,
            @_cIpSis,
            'N',
            @_cMacNicSis
		)

    DECLARE @iTipoPrestamoId INTEGER
    SET @iTipoPrestamoId=@@IDENTITY
    IF @@ERROR<>0 GOTO ErrorCapturado
    SELECT @iTipoPrestamoId AS iTipoPrestamoId

    COMMIT TRANSACTION
    SELECT 1 AS iResult
    RETURN 1
    ErrorCapturado:
    ROLLBACK TRANSACTION
    SELECT 0 AS iResult
    RETURN 0

END
GO
